<?php namespace App\Http\Controllers;

use \DB, \Session, \Validator;
use Illuminate\Http\Request;



class TicketController extends Controller {

    protected $input_rules = [ 'idCustomer' => 'required','idUserRec' => 'required','nameDevice' => 'required' ];
    protected $statusTicket = array('naroceno' => 'Naročeno', 'preklic' => 'Preklicano', 'vteku' => 'V teku', 'narejeno' => 'Narejeno');

    public function __construct(Request $request){
        $this->request = $request;
    }

    private function insertMainSql($id = 0) {
        
        if($this->request->input('idCustomer') > 0){
            $idCustomer = $this->request->input('idCustomer');
        } else {
            $customer = new \App\Customer;
            $customer->name = $this->request->input('name');
            $customer->email = $this->request->input('email');
            $customer->address = $this->request->input('address');
            $customer->tel = $this->request->input('tel');
            $customer->note = $this->request->input('note');
            $customer->save();
            
            $idCustomer = $customer->id;
        }
        if ($id > 0) {
            $ticket = \App\Ticket::find($id);
        } else {
            $ticket = new \App\Ticket;
 //           $ticket->idUser = $this->request->input('idUser');
        }
        $ticket->nameDevice = $this->request->input('nameDevice');
        $ticket->statusTicket = $this->request->input('statusTicket');
        $ticket->dateRec = $this->request->input('dateRec');
        $ticket->dateFin = $this->request->input('dateFin');
        $ticket->material = $this->request->input('material');
        $ticket->workDone = $this->request->input('workDone');
        $ticket->stateRec = $this->request->input('stateRec');
        $ticket->serialDevice = $this->request->input('serialDevice');
        $ticket->pricePredict = $this->request->input('pricePredict');
        $ticket->priceCharged = $this->request->input('priceCharged');
        $ticket->note = $this->request->input('note');
        $ticket->idCustomer = $this->request->input('idCustomer');
        $ticket->idUserRec = $this->request->input('idUserRec');
        $ticket->idUserFin = $this->request->input('idUserFin');
        $ticket->save();
        return $ticket->id;
    }

    public function index() {
        $idCust = $this->request->input('idCustomer');
        $year = $this->request->input('year');

        $q = DB::table('ticket')
                ->select('ticket.id', 'ticket.nameDevice', 'ticket.dateRec', 'ticket.dateFin', 'ticket.statusTicket', 'customer.name as cname','customer.tel','customer.email')
                ->join('customer', 'ticket.idCustomer', '=', 'customer.id');
        if ($idCust > 0) {
            $q->where('idCustomer', $idCust);
        }
        if ($year > 0) {
            $q->where('dateRec', '>', $year . '-0-0');
            $q->where('dateRec', '<', ($year + 1) . '-0-0');
        }
        $ticket = $q->ticketBy('ticket.dateRec','DESC')->get();

        return view('ticket.index')
                        ->with('actOrd', 'active')
                        ->with('customer', array('0' => 'izberi stranko') + DB::table('customer')->ticketBy('name')->lists('name', 'id'))
                        ->with('years', array('0' => 'izberi leto') + array_combine(range(date("Y"), date("Y") - 10), range(date("Y"), date("Y") - 10)))
                        ->with('idCust', $idCust)
                        ->with('year', $year)
                        ->with('obj', $ticket);
    }
    public function create() {
        return view('ticket.form')
                        ->with('formAction', 'ticket.store')
                        ->with('formMethod', 'POST')
                        ->with('items', array())
                        ->with('statusTicket', $this->statusTicket)
                        ->with('users', DB::table('user')->lists('name', 'id'))
                        ->with('actOrd', 'active')
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
        return view('ticket.form')
                        ->with('formAction', 'ticket.update')
                        ->with('formMethod', 'PUT')
                        ->with('displayCancel','inline')
                        ->with('statusTicket', $this->statusTicket)
                        ->with('users', DB::table('user')->lists('name', 'id'))
                        ->with('actOrd', 'active')
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
        Session::flash('message', 'Successfully deleted');
        return redirect('ticket');
    }

}
