<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class CompanyController extends Controller {

    var $input_rules = [ 'name' => 'required'];

    public function __construct(Request $request){
        $this->request = $request;
    }
    
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $company = \App\Company::find($id);
        } else {
            $company = new \App\Company;
        }
        $company->name = $this->request->input('name');
        $company->tel = $this->request->input('tel');
        $company->email = $this->request->input('email');
        $company->address = $this->request->input('address');
        $company->company = $this->request->input('company');
        $company->ddvCompany = $this->request->input('ddvCompany');
        $company->note = $this->request->input('note');
        $company->save();
        
        return $company->id;
    }

    public function index() {
        $nameCust = ($this->request->has('nameCust')) ?  $this->request->input('nameCust') : '';
        
        $company = DB::table('company')->select('company.id','company.name',
                'company.tel','company.email','company.address','company.company','company.ddvCompany','company.note')
                ->where('company.name','LIKE', '%'.$nameCust.'%')
                ->orderBy('company.name')->paginate(30);

        return view('company.index')
                        ->with('actCus', 'active')
                        ->with('nameCust', $nameCust)
                        ->with('company', $company);
    }

    public function create() {
        
        return view('company.form')
                        ->with('formAction', 'company.store')
                        ->with('formMethod', 'POST')
                        ->with('formTitle', 'Vnos stranke')
                        ->with('indCreate', true)
                        ->with('actCus', 'active')
                        ->with('company', new \App\Company);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('company');
    }

    public function edit($id) {
        return view('company.form')
                        ->with('formAction', 'company.update')
                        ->with('formMethod', 'PUT')
                        ->with('formTitle', 'Spremeni vnos')
                        ->with('displayCancel', 'inline')
                        ->with('indCreate', false)
                        ->with('actCus', 'active')
                        ->with('company', \App\Company::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('company');

    }

    public function destroy($id) {
        $cnt=0;
        $cnt += DB::table('order')->where('idCompany',$id)->count();
        if($cnt==0) {
            DB::transaction(function($id) use ($id) {
                DB::table('company')->where('id',$id)->delete();
            });
            Session::flash('message', 'Successfully deleted');
        } else {
            Session::flash('message-err', 'ERR: objekt uporabljen v sistemu');
        }
        return redirect('company');
    }
}
