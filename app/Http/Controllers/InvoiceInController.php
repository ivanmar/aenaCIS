<?php namespace App\Http\Controllers;

use \DB, \Session, PDF, Image;
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
        
        $invoicein->nrInvoice = $this->request->input('nrInvoice');
        $invoicein->idCompany = $this->request->input('idCompany');
        $invoicein->marketplace = $this->request->input('marketplace');
        $invoicein->marketplaceUser = $this->request->input('marketplaceUser');
        $invoicein->webRef = $this->request->input('webRef');
        $invoicein->dateIssue = $this->request->input('dateIssue');
        $invoicein->desc = $this->request->input('desc');
        $invoicein->save();
        
        if (Session::has('sessDataProduct')) {
            $sessDataProduct = Session::get('sessDataProduct');
            foreach( $sessDataProduct as $idProduct => $qty ) {
                $invoiceinart = new \App\InvoiceInArt;
                $priceUnit = DB::table('product')->where('id',$idProduct)->value('priceSelf');
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
        Session::forget('sessDataProduct');
        
        return $invoiceinart->id;
    }
    public function index() {
        $invoicein = \App\InvoiceIn::with('company')->get();
        Session::forget('sessDataProduct');
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
                Session::put('sessDataProduct', array_add($sessDataProduct = Session::get('sessDataProduct'), $val->idProduct, $val->qty));
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

    public function show($id) {
        $invoicein = \App\InvoiceOut::find($id);
        $company = \App\Company::find($invoicein->idCompany);

        $items = DB::table('invoiceInArt')
                ->select('invoiceInArt.priceUnit','invoiceInArt.qty','invoiceInArt.idProduct')
                ->where('idInvoiceIn', $id)->get();

        $data = array(
            'company' => $company->name, 'nrInvoice' => $invoicein->nrInvoice,'dateIssue'=>$invoicein->dateIssue,'dateDue'=>$invoicein->dateDue,
            'address' => $company->address, 'zipCode' => $company->zipCode, 'city' => $company->city,
            'items' => $items
        );
        $pdf = PDF::loadView('pdfgen.invoiceOut0', $data);
        return $pdf->stream($invoicein->nrInvoice.'-racun.pdf');
    }
    
    public function destroy($id) {
        \App\InvoiceInArt::where('idInvoiceIn', '=', $id)->delete();

        \App\InvoiceIn::find($id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('invoicein');
    }

}
