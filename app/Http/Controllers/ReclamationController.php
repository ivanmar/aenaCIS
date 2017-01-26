<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ReclamationController extends Controller {

    var $input_rules = ['dateStart' => 'required','idProduct' => 'required'];
    protected $statusRecl = array('sprejeto' => 'Sprejeto', 'refunt' => 'Refundirano', 'preklic' => 'Preklicano');

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $reclamation = \App\Reclamation::find($id);
        } else {
            $reclamation = new \App\Reclamation;
        }
        
        $reclamation->idCompany = $this->request->input('idCompany');
        $reclamation->idContact = $this->request->input('idContact');
        $reclamation->dateStart = $this->request->input('dateStart');
        $reclamation->idProduct = $this->request->input('idProduct');
        $reclamation->status = $this->request->input('status');
        $reclamation->idInvoiceOut = $this->request->input('idInvoiceOut');
        $reclamation->desc = $this->request->input('desc');
        $reclamation->save();
        return $reclamation->id;
    }
    public function index() {
        $reclamation = \App\Reclamation::with('company','contact','product')->get();
        return view('reclamation.index')
                        ->with('actInvo', 'active')
                        ->with('obj', $reclamation);
    }

    public function create() {
        $products = array('0' => 'izberi produkt') + DB::table('product')->pluck('name','id')->toArray();
        $contact = array('0' => 'izberi') + DB::table('contact')->pluck('name','id')->toArray();
        $company = array('0' => 'izberi') + DB::table('company')->pluck('name','id')->toArray();

        return view('reclamation.form')
                        ->with('formAction', 'reclamation.store')
                        ->with('formMethod', 'POST')
                        ->with('contact', $contact)
                        ->with('company', $company)
                        ->with('products', $products)
                        ->with('status', $this->statusRecl)
                        ->with('actInvo', 'active')
                        ->with('obj', new \App\Reclamation);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('reclamation');
    }
    
    public function edit($id) {
        $products = array('0' => 'izberi produkt') + DB::table('product')->pluck('name','id')->toArray();
        $contact = array('0' => 'izberi') + DB::table('contact')->pluck('name','id')->toArray();
        $company = array('0' => 'izberi') + DB::table('company')->pluck('name','id')->toArray();

        return view('reclamation.form')
                        ->with('formAction', 'reclamation.update')
                        ->with('formMethod', 'PUT')
                        ->with('contact', $contact)
                        ->with('company', $company)
                        ->with('products', $products)
                        ->with('status', $this->statusRecl)
                        ->with('actInvo', 'active')
                        ->with('obj', \App\Reclamation::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('reclamation');
    }
    
    public function destroy($id) {
        DB::table('reclamation')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('reclamation');
    }

}
