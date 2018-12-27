<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ContactController extends Controller {

    var $input_rules = [ 'name' => 'required'];

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
    }
    
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $contact = \App\Contact::find($id);
        } else {
            $contact = new \App\Contact;
        }
        $contact->name = $this->request->input('name');
        $contact->tel = $this->request->input('tel');
        $contact->email = $this->request->input('email');
        $contact->address = $this->request->input('address');
        $contact->idCompany = $this->request->input('idCompany');
        $contact->city = $this->request->input('city');
        $contact->zipCode = $this->request->input('zipCode');
        $contact->note = $this->request->input('note');
        $contact->save();
        
        return $contact->id;
    }

    public function index() {
        $nameCont = ($this->request->has('nameCont')) ?  $this->request->input('nameCont') : '';
        
        $contact = DB::table('contact')->select('contact.id','contact.name',
                'contact.tel','contact.email','contact.address','contact.city','contact.note','company.name as compName')
                ->leftJoin('company','contact.idCompany','=','company.id')
                ->where('contact.name','LIKE', '%'.$nameCont.'%')
                ->orderBy('contact.name')->paginate(30);

        return view('contact.index')
                        ->with('actCont', 'active')
                        ->with('nameCont', $nameCont)
                        ->with('contact', $contact);
    }

    public function create() {
        $compList = array(''=>'končni kupec') + DB::table('company')->orderBy('name')->pluck('name','id')->toArray();
        return view('contact.form')
                        ->with('formAction', 'contact.store')
                        ->with('formMethod', 'POST')
                        ->with('formTitle', 'Vnos kontakta')
                        ->with('indCreate', true)
                        ->with('compList',$compList)
                        ->with('actCont', 'active')
                        ->with('contact', new \App\Contact);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('contact');
    }

    public function edit($id) {
        $compList = array(''=>'končni kupec') + DB::table('company')->orderBy('name')->pluck('name','id')->toArray();
        return view('contact.form')
                        ->with('formAction', 'contact.update')
                        ->with('formMethod', 'PUT')
                        ->with('formTitle', 'Spremeni vnos')
                        ->with('displayCancel', 'inline')
                        ->with('compList',$compList)
                        ->with('indCreate', false)
                        ->with('actCont', 'active')
                        ->with('contact', \App\Contact::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('contact');

    }

    public function destroy($id) {
        $cnt=0;
        $cnt += DB::table('ticket')->where('idContact',$id)->count();
        if($cnt==0) {
            DB::transaction(function() use ($id) {
                DB::table('contact')->where('id',$id)->delete();
            });
            Session::flash('message', 'Successfully deleted');
        } else {
            Session::flash('message-err', 'ERR: objekt uporabljen v sistemu');
        }
        return redirect('contact');
    }
}
