<?php namespace App\Http\Controllers;

use \DB, \Session, \Validator;
use Illuminate\Http\Request;



class OrderController extends Controller {

    protected $input_rules = [ 'idCustomer' => 'required','idUserRec' => 'required','nameDevice' => 'required' ];
    protected $statusOrder = array('naroceno' => 'Naročeno', 'preklic' => 'Preklicano', 'vteku' => 'V teku', 'narejeno' => 'Narejeno');

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
            $order = \App\Order::find($id);
        } else {
            $order = new \App\Order;
 //           $order->idUser = $this->request->input('idUser');
        }
        $order->nameDevice = $this->request->input('nameDevice');
        $order->statusOrder = $this->request->input('statusOrder');
        $order->dateRec = $this->request->input('dateRec');
        $order->dateFin = $this->request->input('dateFin');
        $order->material = $this->request->input('material');
        $order->workDone = $this->request->input('workDone');
        $order->stateRec = $this->request->input('stateRec');
        $order->serialDevice = $this->request->input('serialDevice');
        $order->pricePredict = $this->request->input('pricePredict');
        $order->priceCharged = $this->request->input('priceCharged');
        $order->note = $this->request->input('note');
        $order->idCustomer = $this->request->input('idCustomer');
        $order->idUserRec = $this->request->input('idUserRec');
        $order->idUserFin = $this->request->input('idUserFin');
        $order->save();
        return $order->id;
    }

    public function index() {
        $idCust = $this->request->input('idCustomer');
        $year = $this->request->input('year');

        $q = DB::table('order')
                ->select('order.id', 'order.nameDevice', 'order.dateRec', 'order.dateFin', 'order.statusOrder', 'customer.name as cname','customer.tel','customer.email')
                ->join('customer', 'order.idCustomer', '=', 'customer.id');
        if ($idCust > 0) {
            $q->where('idCustomer', $idCust);
        }
        if ($year > 0) {
            $q->where('dateRec', '>', $year . '-0-0');
            $q->where('dateRec', '<', ($year + 1) . '-0-0');
        }
        $order = $q->orderBy('order.dateRec','DESC')->get();

        return view('order.index')
                        ->with('actOrd', 'active')
                        ->with('customer', array('0' => 'izberi stranko') + DB::table('customer')->orderBy('name')->lists('name', 'id'))
                        ->with('years', array('0' => 'izberi leto') + array_combine(range(date("Y"), date("Y") - 10), range(date("Y"), date("Y") - 10)))
                        ->with('idCust', $idCust)
                        ->with('year', $year)
                        ->with('obj', $order);
    }
    public function create() {
        return view('order.form')
                        ->with('formAction', 'order.store')
                        ->with('formMethod', 'POST')
                        ->with('items', array())
                        ->with('statusOrder', $this->statusOrder)
                        ->with('users', DB::table('user')->lists('name', 'id'))
                        ->with('actOrd', 'active')
                        ->with('obj', new \App\Order );
    }
    public function store() {
        $v = Validator::make($this->request->all(), $this->input_rules);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('order');
        
    }

    public function edit($id) {
        return view('order.form')
                        ->with('formAction', 'order.update')
                        ->with('formMethod', 'PUT')
                        ->with('displayCancel','inline')
                        ->with('statusOrder', $this->statusOrder)
                        ->with('users', DB::table('user')->lists('name', 'id'))
                        ->with('actOrd', 'active')
                        ->with('obj', \App\Order::find($id));
    }
    public function update($id) {
        $v = Validator::make($this->request->all(), $this->input_rules);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('order');

    }

    public function destroy($id) {
        DB::table('order')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('order');
    }

}
