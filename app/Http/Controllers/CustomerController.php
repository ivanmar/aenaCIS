<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class CustomerController extends Controller {

    var $input_rules = [ 'name' => 'required'];

    public function __construct(Request $request){
        $this->request = $request;
    }
    
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $customer = \App\Customer::find($id);
        } else {
            $customer = new \App\Customer;
        }
        $customer->name = $this->request->input('name');
        $customer->tel = $this->request->input('tel');
        $customer->email = $this->request->input('email');
        $customer->address = $this->request->input('address');
        $customer->company = $this->request->input('company');
        $customer->ddvCompany = $this->request->input('ddvCompany');
        $customer->note = $this->request->input('note');
        $customer->save();
        
        return $customer->id;
    }

    public function index() {
        $nameCust = ($this->request->has('nameCust')) ?  $this->request->input('nameCust') : '';
        
        $customer = DB::table('customer')->select('customer.id','customer.name',
                'customer.tel','customer.email','customer.address','customer.company','customer.ddvCompany','customer.note')
                ->where('customer.name','LIKE', '%'.$nameCust.'%')
                ->orderBy('customer.name')->paginate(30);

        return view('customer.index')
                        ->with('actCus', 'active')
                        ->with('nameCust', $nameCust)
                        ->with('customer', $customer);
    }

    public function create() {
        
        return view('customer.form')
                        ->with('formAction', 'customer.store')
                        ->with('formMethod', 'POST')
                        ->with('formTitle', 'Vnos stranke')
                        ->with('indCreate', true)
                        ->with('actCus', 'active')
                        ->with('customer', new \App\Customer);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('customer');
    }

    public function edit($id) {
        return view('customer.form')
                        ->with('formAction', 'customer.update')
                        ->with('formMethod', 'PUT')
                        ->with('formTitle', 'Spremeni vnos')
                        ->with('displayCancel', 'inline')
                        ->with('indCreate', false)
                        ->with('actCus', 'active')
                        ->with('customer', \App\Customer::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('customer');

    }

    public function destroy($id) {
        $cnt=0;
        $cnt += DB::table('order')->where('idCustomer',$id)->count();
        if($cnt==0) {
            DB::transaction(function($id) use ($id) {
                DB::table('customer')->where('id',$id)->delete();
            });
            Session::flash('message', 'Successfully deleted');
        } else {
            Session::flash('message-err', 'ERR: objekt uporabljen v sistemu');
        }
        return redirect('customer');
    }
}
