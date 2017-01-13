<?php namespace App\Http\Controllers;

use \DB, \Session, PDF;
use Illuminate\Http\Request;

class InvoiceOutController extends Controller {

    var $input_rules = ['idCompany' => 'required'];

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $Invoiceout = \App\InvoiceOut::find($id);
        } else {
            $Invoiceout = new \App\InvoiceOut;
        }
        
        $Invoiceout->nrInvoice = $this->request->input('nrInvoice');
        $Invoiceout->idCompany = $this->request->input('idCompany');
        $Invoiceout->dateIssue = $this->request->input('dateIssue');
        $Invoiceout->desc = $this->request->input('desc');
        $Invoiceout->save();
        
        if (Session::has('sessDataProduct')) {
            $sessDataProduct = Session::get('sessDataProduct');
            foreach( $sessDataProduct as $idProduct => $qty ) {
                $Invoiceoutart = new \App\InvoiceOutArt;
                $priceUnit = DB::table('product')->where('id',$idProduct)->value('priceSelf');
                $Invoiceoutart->idProduct = $idProduct;
                $Invoiceoutart->idInvoiceOut = $Invoiceout->id;
                $Invoiceoutart->qty = $qty;
                $Invoiceoutart->priceUnit = $priceUnit;
                $Invoiceoutart->save();
            }
        }
        if (Session::has('sessDataService')) {
            $sessDataService = Session::get('sessDataService');
            foreach( $sessDataService as $idService => $qty ) {
                $Invoiceoutart = new \App\InvoiceOutArt;
                $priceUnit = DB::table('service')->where('id',$idService)->value('priceUnit');
                $Invoiceoutart->idService = $idService;
                $Invoiceoutart->idInvoiceOut = $Invoiceout->id;
                $Invoiceoutart->qty = $qty;
                $Invoiceoutart->priceUnit = $priceUnit;
                $Invoiceoutart->save();
            }
        }
        Session::forget('sessDataProduct');
        Session::forget('sessDataService');
        return $Invoiceoutart->id;
    }
    public function index() {
        $invoiceout = \App\InvoiceOut::with('contact','company')->get();
        return view('invoiceOut.index')
                        ->with('actInvo', 'active')
                        ->with('obj', $invoiceout);
    }

    public function create() {
        $services = array('0' => 'izberi storitev') + DB::table('service')->pluck('name','id')->toArray();
        $products = array('0' => 'izberi produkt') + DB::table('product')->pluck('name','id')->toArray();
        $customer = array('0' => 'končni kupec') + DB::table('company')->pluck('name','id')->toArray();
        $lastNrInvoice = DB::table('invoiceOut')->max('nrInvoice');
        return view('invoiceOut.form')
                        ->with('formAction', 'invoiceout.store')
                        ->with('formMethod', 'POST')
                        ->with('customer', $customer)
                        ->with('services', $services)
                        ->with('products', $products)
                        ->with('lastNrInvoice', $lastNrInvoice + 1)
                        ->with('actInvo', 'active')
                        ->with('obj', new \App\InvoiceOut);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('invoiceout');
    }
    
    public function edit($id) {
        $services = DB::table('service')->pluck('name','id')->toArray();
        $products = DB::table('product')->pluck('name','id')->toArray();
        $customer = array('0' => 'končni kupec') + DB::table('company')->pluck('name','id')->toArray();
        $invoiceoutart = DB::table('invoiceOutArt')->where('idInvoiceOut',$id)->get();
        foreach($invoiceoutart as $key => $val) {
            if($val->idProduct > 0){
                Session::put('sessDataProduct', array_add($sessDataProduct = Session::get('sessDataProduct'), $val->idProduct, $val->qty));
            }
            elseif($val->idService > 0) {
                Session::put('sessDataService', array_add($sessDataService = Session::get('sessDataService'), $val->idService, $val->qty));
            }
        }
        return view('invoiceOut.form')
                        ->with('formAction', 'invoiceout.update')
                        ->with('formMethod', 'PUT')
                        ->with('customer', $customer)
                        ->with('services', $services)
                        ->with('products', $products)
                        ->with('displayCancel', 'true')
                        ->with('actInvo', 'active')
                        ->with('obj', \App\InvoiceOut::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('invoiceout');
    }

    public function show($id) {
        $invoiceout = \App\InvoiceOut::find($id);
        $company = \App\Company::find($invoiceout->idCompany);

        $items = DB::table('invoiceOutArt')
                ->select('invoiceOutArt.priceUnit','invoiceOutArt.qty','invoiceOutArt.idProduct','invoiceOutArt.idService')
                ->where('idInvoiceOut', $id)->get();

        $data = array(
            'company' => $company->name, 'nrInvoice' => $invoiceout->nrInvoice,'dateIssue'=>$invoiceout->dateIssue,'dateDue'=>$invoiceout->dateDue,
            'address' => $company->address, 'zipCode' => $company->zipCode, 'city' => $company->city,
            'items' => $items
        );
        $pdf = PDF::loadView('pdfgen.invoiceOut0', $data);
        return $pdf->stream($invoiceout->nrInvoice.'-racun.pdf');
    }
    
    public function destroy($id) {
        \App\InvoiceOutArt::where('idInvoiceOut', '=', $id)->delete();

        \App\InvoiceOut::find($id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('Invoiceout');
    }

}
