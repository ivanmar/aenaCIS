<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ContactController extends Controller {

    var $input_rules = [ 'name' => 'required'];

    public function __construct(Request $request){
        $this->request = $request;
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
        $contact->company = $this->request->input('company');
        $contact->ddvCompany = $this->request->input('ddvCompany');
        $contact->note = $this->request->input('note');
        $contact->save();
        
        return $contact->id;
    }

    public function index() {
        $nameCust = ($this->request->has('nameCust')) ?  $this->request->input('nameCust') : '';
        
        $contact = DB::table('contact')->select('contact.id','contact.name',
                'contact.tel','contact.email','contact.address','contact.company','contact.ddvCompany','contact.note')
                ->where('contact.name','LIKE', '%'.$nameCust.'%')
                ->orderBy('contact.name')->paginate(30);

        return view('contact.index')
                        ->with('actCus', 'active')
                        ->with('nameCust', $nameCust)
                        ->with('contact', $contact);
    }

    public function create() {
        
        return view('contact.form')
                        ->with('formAction', 'contact.store')
                        ->with('formMethod', 'POST')
                        ->with('formTitle', 'Vnos stranke')
                        ->with('indCreate', true)
                        ->with('actCus', 'active')
                        ->with('contact', new \App\Contact);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('contact');
    }

    public function edit($id) {
        return view('contact.form')
                        ->with('formAction', 'contact.update')
                        ->with('formMethod', 'PUT')
                        ->with('formTitle', 'Spremeni vnos')
                        ->with('displayCancel', 'inline')
                        ->with('indCreate', false)
                        ->with('actCus', 'active')
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
        $cnt += DB::table('order')->where('idContact',$id)->count();
        if($cnt==0) {
            DB::transaction(function($id) use ($id) {
                DB::table('contact')->where('id',$id)->delete();
            });
            Session::flash('message', 'Successfully deleted');
        } else {
            Session::flash('message-err', 'ERR: objekt uporabljen v sistemu');
        }
        return redirect('contact');
    }
}
