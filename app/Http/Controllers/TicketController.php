<?php namespace App\Http\Controllers;

use \DB, \Session, \Validator, \Auth, \PDF;
use Illuminate\Http\Request;



class TicketController extends Controller {

    protected $input_rules = [ 'idContact' => 'required','name' => 'required' ];
    protected $statusTicket = array('open' => 'Odprto', 'cancel' => 'Preklicano', 'progress' => 'V teku', 'done' => 'Narejeno');

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
    }

    private function insertMainSql($id = 0) {
        
        if($this->request->input('idContact') > 0){
            $idContact = $this->request->input('idContact');
        } else {
            $contact = new \App\Contact;
            $contact->name = $this->request->input('name');
            $contact->email = $this->request->input('email');
            $contact->address = $this->request->input('address');
            $contact->city = $this->request->input('city');
            $contact->zipCode = $this->request->input('zipCode');
            $contact->tel = $this->request->input('tel');
            $contact->idCompany = $this->request->input('idCompany');
            $contact->save();
            
            $idContact = $contact->id;
        }
        if ($id > 0) {
            $ticket = \App\Ticket::find($id);
        } else {
            $ticket = new \App\Ticket;
        }
        $ticket->name = $this->request->input('name');
        $ticket->status = $this->request->input('status');
        $ticket->dateOpen = $this->request->input('dateOpen');
        $ticket->dateClose = $this->request->input('dateClose');
        $ticket->partUsed = $this->request->input('partUsed');
        $ticket->ticketDesc = $this->request->input('ticketDesc');
        $ticket->ticketRes = $this->request->input('ticketRes');
        $ticket->serialDevice = $this->request->input('serialDevice');
        $ticket->pricePredict = $this->request->input('pricePredict');
        $ticket->note = $this->request->input('note');
        $ticket->idContact = $this->request->input('idContact');
        $ticket->idUser = Auth::user();
        $ticket->save();
        return $ticket->id;
    }
    public function show($id) {
        $data = (array) DB::table('ticket')->select('ticket.id','ticket.name','ticket.dateOpen','ticket.dateClose',
                                            'ticket.ticketDesc','ticket.ticketRes','ticket.partUsed','ticket.note',
                                            'ticket.indBag','ticket.indCharger',
                                            'contact.name as cname','contact.address','contact.tel','contact.email',
                                            'users.name as uname')
                    ->join('contact','ticket.idContact','=','contact.id')
                    ->join('users','ticket.idUser','=','users.id')
                    ->where('ticket.id',$id)->first();
        $pdf = PDF::loadView('pdfgen.ticket0', $data);
        $pdf->download('ticket_' .$id . '.pdf');
    }

    public function index() {
        $idContact = $this->request->input('idContact');
        $year = $this->request->input('year');

        $q = DB::table('ticket')
                ->select('ticket.id', 'ticket.name', 'ticket.dateOpen', 'ticket.dateClose', 'ticket.status', 'contact.name as cname','contact.tel','contact.email')
                ->join('contact', 'ticket.idContact', '=', 'contact.id');
        if ($idContact > 0) {
            $q->where('idContact', $idContact);
        }
        if ($year > 0) {
            $q->where('dateOpen', '>', $year . '-0-0');
            $q->where('dateClose', '<', ($year + 1) . '-0-0');
        }
        $ticket = $q->orderBy('ticket.dateOpen','DESC')->get();

        return view('ticket.index')
                        ->with('actTick', 'active')
                        ->with('contact', array('0' => 'izberi stranko') + DB::table('contact')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('years', array('0' => 'izberi leto') + array_combine(range(date("Y"), date("Y") - 10), range(date("Y"), date("Y") - 10)))
                        ->with('idContact', $idContact)
                        ->with('year', $year)
                        ->with('obj', $ticket);
    }
    public function create() {
        $compList = array(''=>'končni kupec') + DB::table('company')->pluck('name','id')->toArray();
        return view('ticket.form')
                        ->with('formAction', 'ticket.store')
                        ->with('formMethod', 'POST')
                        ->with('items', array())
                        ->with('status', $this->statusTicket)
                        ->with('users', DB::table('users')->pluck('name', 'id'))
                        ->with('actTick', 'active')
                        ->with('compList',$compList)
                        ->with('obj', new \App\Ticket );
    }
    public function store() {
        $v = Validator::make($this->request->all(), $this->input_rules);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('ticket');
        
    }

    public function edit($id) {
        $compList = array(''=>'končni kupec') + DB::table('company')->pluck('name','id')->toArray();
        $comments=DB::table('comment')->select('comment.text','comment.dateTime','users.name AS username')
                    ->join('users','comment.idUser','=','users.id')
                    ->where('idTicket',$id)->orderBy('dateTime')->get();
        return view('ticket.form')
                        ->with('formAction', 'ticket.update')
                        ->with('formMethod', 'PUT')
                        ->with('status', $this->statusTicket)
                        ->with('users', DB::table('users')->pluck('name', 'id'))
                        ->with('actTick', 'active')
                        ->with('compList',$compList)
                        ->with('comments',$comments)
                        ->with('obj', \App\Ticket::find($id));
    }
    public function update($id) {
        $v = Validator::make($this->request->all(), $this->input_rules);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('ticket');

    }

    public function destroy($id) {
        DB::table('ticket')->where('id',$id)->delete();
        DB::table('comment')->where('idTicket',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('ticket');
    }

}
