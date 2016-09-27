<?php namespace App\Http\Controllers;

use \DB;
use Illuminate\Http\Request;

class JsController extends Controller {

    public function __construct(Request $request){
        $this->request = $request;
    }
    
    public function index() { }
    
    public function getCustList($term) {
        $custList = DB::table('customer')
                ->select('name as label','id as value')
                ->where('name','LIKE',$term.'%')
                ->orderBy('name')->get();
        return response()->json($custList);
    }
    public function addCust () {
        $customer = new \App\Customer;
        $customer->name = $this->request->input('name');
        $customer->tel = $this->request->input('tel');
        $customer->email = $this->request->input('email');
        $customer->address = $this->request->input('address');
        $customer->company = $this->request->input('company');
        $customer->ddvCompany = $this->request->input('ddvCompany');
        $customer->save();
        return response()->json($customer->id);
    }

}
