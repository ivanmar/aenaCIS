<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class CompanyController extends Controller {

    var $input_rules = [ 'name' => 'required'];

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
    }
    
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $company = \App\Company::find($id);
        } else {
            $company = new \App\Company;
        }
        $company->name = $this->request->input('name');
        $company->phone = $this->request->input('phone');
        $company->email = $this->request->input('email');
        $company->address = $this->request->input('address');
        $company->zipCode = $this->request->input('zipCode');
        $company->city = $this->request->input('city');
        $company->country = $this->request->input('country');
        $company->ddvCode = $this->request->input('ddvCode');
        $company->regCode = $this->request->input('regCode');
        $company->bankAccount = $this->request->input('bankAccount');
        $company->b2bAccess = $this->request->input('b2bAccess');
        $company->contactName = $this->request->input('contactName');
        $company->note = $this->request->input('note');
        
        $company->indTax = ($this->request->input('indTax')) ? 1 : 0;
        $company->indVendor = ($this->request->input('indVendor')) ? 1 : 0;

        $company->save();
        
        return $company->id;
    }

    public function index() {
        $nameComp = ($this->request->has('nameComp')) ?  $this->request->input('nameComp') : '';
        
        $company = DB::table('company')->select('company.id','company.name',
                'company.phone','company.email','company.address','company.city','company.ddvCode','company.note')
                ->where('company.name','LIKE', '%'.$nameComp.'%')
                ->orderBy('company.name')->paginate(40);

        return view('company.index')
                        ->with('actComp', 'active')
                        ->with('nameComp', $nameComp)
                        ->with('company', $company);
    }

    public function create() {
        return view('company.form')
                        ->with('formAction', 'company.store')
                        ->with('formMethod', 'POST')
                        ->with('formTitle', 'Vnos podjetja')
                        ->with('indCreate', true)
                        ->with('actComp', 'active')
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
                        ->with('actComp', 'active')
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
        $cnt += DB::table('ticket')->where('idCompany',$id)->count();
        $cnt += DB::table('project')->where('idCompany',$id)->count();
        $cnt += DB::table('invoiceIN')->where('idCompany',$id)->count();
        $cnt += DB::table('invoiceOUT')->where('idCompany',$id)->count();
        $cnt += DB::table('contact')->where('idCompany',$id)->count();
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
