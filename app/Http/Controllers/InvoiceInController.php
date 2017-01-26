<?php namespace App\Http\Controllers;

use \DB, \Session, Image;
use Illuminate\Http\Request;

class InvoiceInController extends Controller {

    var $input_rules = ['idCompany' => 'required|integer|min:1'];
    var $marketplaces = array( 'none'=>'Noben' ,'ebay'=>'ebay','aliexpress'=>'Aliexpress');
    var $paidWith = array( 'none'=>'Izberi' ,'paypal'=>'paypal','trr'=>'TRR','cash'=>'Gotovina');

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $invoicein = \App\InvoiceIn::find($id);
        } else {
            $invoicein = new \App\InvoiceIn;
        }
        
        if ($this->request->hasFile('scan0')) {
            $file = $this->request->file('scan0');
            $destinationPath = 'public/upload/image/';
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = substr(str_shuffle('abcefghijklmnopqrstuvwxyz1234567890'), 0, 14).'.'.$extension;
            $file->move($destinationPath, $filename);
            Image::make($destinationPath . $filename)->resize(2339, 1654)->save($destinationPath . $filename);
            $invoicein->scan0 = $filename;
        }
        if ($this->request->hasFile('pdf0')) {
            $file = $this->request->file('pdf0');
            $destinationPath = 'public/upload/pdf/';
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = substr(str_shuffle('abcefghijklmnopqrstuvwxyz1234567890'), 0, 14).'.'.$extension;
            $file->move($destinationPath, $filename);
            $invoicein->pdf0 = $filename;
        }
        
        $invoicein->nrInvoice = $this->request->input('nrInvoice');
        $invoicein->idCompany = $this->request->input('idCompany');
        $invoicein->marketplace = $this->request->input('marketplace');
        $invoicein->marketplaceUser = $this->request->input('marketplaceUser');
        $invoicein->webRef = $this->request->input('webRef');
        $invoicein->dateIssue = $this->request->input('dateIssue');
        $invoicein->shipCost = $this->request->input('shipCost');
        $invoicein->desc = $this->request->input('desc');
        $invoicein->save();
        
        if (Session::has('sessProductIn')) {
            $sessProductIn= Session::get('sessProductIn');
            foreach( $sessProductIn as $idProduct => $key ) {
                $qty = $key['qty'];
                $priceUnit = $key['priceUnit'];
                $invoiceinart = new \App\InvoiceInArt;
                $invoiceinart->idProduct = $idProduct;
                $invoiceinart->idInvoiceIn = $invoicein->id;
                $invoiceinart->qty = $qty;
                $invoiceinart->priceUnit = $priceUnit;
                $invoiceinart->save();
                for($i=0; $i< $qty; $i++) {
                    $productinout = new \App\ProductInOut;
                    $productinout->idProduct = $idProduct;
                    $productinout->idInvoiceInArt = $invoiceinart->id;
                    $productinout->dateIn = $invoicein->dateIssue;
                    $productinout->save();
                }
            }
        }
        Session::forget('sessProductIn');
        
        return $invoiceinart->id;
    }
    public function index() {
        $invoicein = \App\InvoiceIn::with('company')->get();
        Session::forget('sessDataProduct');
        Session::forget('sessProductIn');
        return view('invoiceIn.index')
                        ->with('actInvo', 'active')
                        ->with('obj', $invoicein);
    }

    public function create() {
        $products = array('0' => 'izberi produkt') + DB::table('product')->pluck('name','id')->toArray();
        $customer = array('0' => 'izberi dobavitelja') + DB::table('company')->where('indVendor',1)->pluck('name','id')->toArray();

        return view('invoiceIn.form')
                        ->with('formAction', 'invoicein.store')
                        ->with('formMethod', 'POST')
                        ->with('customer', $customer)
                        ->with('products', $products)
                        ->with('marketplaces', $this->marketplaces)
                        ->with('actInvo', 'active')
                        ->with('obj', new \App\InvoiceIn);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('invoicein');
    }
    
    public function edit($id) {
        $products = DB::table('product')->pluck('name','id')->toArray();
        $customer = DB::table('company')->where('indVendor',1)->pluck('name','id')->toArray();
        
        $invoiceinart = DB::table('invoiceInArt')->where('idInvoiceIn',$id)->get();
        foreach($invoiceinart as $val) {
            if($val->idProduct > 0){
                $tmparr=array('qty'=>$val->qty, 'priceUnit'=>$val->priceUnit);
                Session::put('sessProductIn', array_add($sessProductIn = Session::get('sessProductIn'), $val->idProduct, $tmparr));
            }
        }
        return view('invoiceIn.form')
                        ->with('formAction', 'invoicein.update')
                        ->with('formMethod', 'PUT')
                        ->with('customer', $customer)
                        ->with('products', $products)
                        ->with('marketplaces', $this->marketplaces)
                        ->with('displayCancel', 'true')
                        ->with('actInvo', 'active')
                        ->with('obj', \App\InvoiceIn::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('invoicein');
    }
    
    public function destroy($id) {
        $getValArt=DB::table('invoiceInArt')->where('idInvoiceIn',$id)->get();
        foreach($getValArt as $valart){
            DB::table('productInOut')->where('idInvoiceInArt',$valart->id)->delete();
        }
        DB::table('invoiceInArt')->where('idInvoiceIn',$id)->delete();
        DB::table('invoiceIn')->where('id',$id)->delete();
        
        Session::flash('message', 'Successfully deleted');
        return redirect('invoicein');
    }

}
