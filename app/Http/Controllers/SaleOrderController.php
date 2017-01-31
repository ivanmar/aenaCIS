<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class SaleOrderController extends Controller {

    var $input_rules = ['dateOrder' => 'required'];
    protected $statusOrder = array('sprejeto' => 'Sprejeto', 'naroceno' => 'Naročeno', 'preklic' => 'Preklicano', 'narejeno' => 'Narejeno');
    protected $orderOrigin = array('eshop' => 'Online shop', 'phone' => 'Telefon', 'email' => 'E-mail', 'servis' => 'Servis');

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $saleorder = \App\SaleOrder::find($id);
        } else {
            $saleorder = new \App\SaleOrder;
        }
        
        $saleorder->idCompany = $this->request->input('idCompany');
        $saleorder->idContact = $this->request->input('idContact');
        $saleorder->dateOrder = $this->request->input('dateOrder');
        $saleorder->dateFor = $this->request->input('dateFor');
        $saleorder->priceNet = $this->request->input('priceNet');
        $saleorder->shipCost = $this->request->input('shipCost');
        $saleorder->status = $this->request->input('status');
        $saleorder->orderOrigin = $this->request->input('orderOrigin');
        $saleorder->orderPlaced = $this->request->input('orderPlaced');
        $saleorder->desc = $this->request->input('desc');
        $saleorder->save();
        
        if (Session::has('sessDataProduct')) {
            $sessDataProduct = Session::get('sessDataProduct');
            foreach( $sessDataProduct as $idProduct => $qty ) {
                $saleorderart = new \App\SaleOrderArt;
                $priceUnit = DB::table('product')->where('id',$idProduct)->value('priceSelf');
                $saleorderart->idProduct = $idProduct;
                $saleorderart->idSaleOrder = $saleorder->id;
                $saleorderart->qty = $qty;
                $saleorderart->priceUnit = $priceUnit;
                $saleorderart->save();
            }
        }
        Session::forget('sessDataProduct');
        return $saleorder->id;
    }
    public function index() {
        $saleorder = \App\SaleOrder::with('company')->get();
        Session::forget('sessDataProduct');
        Session::forget('sessProductIn');
        Session::forget('sessDataService');
        return view('saleOrder.index')
                        ->with('actSale', 'active')
                        ->with('obj', $saleorder);
    }

    public function create() {
        $products = array('0' => 'izberi produkt') + DB::table('product')->pluck('name','id')->toArray();
        $compList = array('0' => 'izberi') + DB::table('company')->pluck('name','id')->toArray();

        return view('saleOrder.form')
                        ->with('formAction', 'saleorder.store')
                        ->with('formMethod', 'POST')
                        ->with('compList', $compList)
                        ->with('products', $products)
                        ->with('status', $this->statusOrder)
                        ->with('orderOrigin', $this->orderOrigin)
                        ->with('actSale', 'active')
                        ->with('obj', new \App\SaleOrder);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('saleorder');
    }
    
    public function edit($id) {
        $products = array('0' => 'izberi produkt') + DB::table('product')->pluck('name','id')->toArray();
        $compList = array('0' => 'izberi') + DB::table('company')->pluck('name','id')->toArray();
        $saleorderart = DB::table('saleOrderArt')->where('idSaleOrder',$id)->get();
        foreach($saleorderart as $key => $val) {
            if($val->idProduct > 0){
                Session::put('sessDataProduct', array_add($sessDataProduct = Session::get('sessDataProduct'), $val->idProduct, $val->qty));
            }
        }
        return view('saleOrder.form')
                        ->with('formAction', 'saleorder.update')
                        ->with('formMethod', 'PUT')
                        ->with('compList', $compList)
                        ->with('products', $products)
                        ->with('status', $this->statusOrder)
                        ->with('orderOrigin', $this->orderOrigin)
                        ->with('actSale', 'active')
                        ->with('obj', \App\SaleOrder::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('saleorder');
    }
    
    public function destroy($id) {
        DB::table('saleOrderArt')->where('idSaleOrder',$id)->delete();
        DB::table('saleOrder')->where('id',$id)->delete();
        
        Session::flash('message', 'Successfully deleted');
        return redirect('saleorder');
    }

}
