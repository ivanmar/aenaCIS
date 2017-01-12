<?php namespace App\Http\Controllers;

use \DB, \Session, PDF;
use Illuminate\Http\Request;

class InvoiceOutController extends Controller {

    var $input_rules = ['idCompany' => 'required'];

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
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
        return view('invoiceOut.form')
                        ->with('formAction', 'invoiceout.store')
                        ->with('formMethod', 'POST')
                        ->with('customer', $customer)
                        ->with('services', $services)
                        ->with('products', $products)
                        ->with('actInvo', 'active')
                        ->with('obj', new \App\InvoiceOut);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $Invoiceout = new \App\InvoiceOut;
        $Invoiceout->nrInvoice = 'R' . date('Ymd-His');
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
        Session::flash('message', 'Successfully created');
        Session::forget('sessDataProduct');
        Session::forget('sessDataService');
        return redirect('invoiceout');
    }
    
    public function edit($id) {
        $services = DB::table('service')->pluck('name','id')->toArray();
        $products = DB::table('product')->pluck('name','id')->toArray();
        $customer = array('0' => 'končni kupec') + DB::table('company')->pluck('name','id')->toArray();
        return view('Invoiceout.form')
                        ->with('formAction', 'Invoiceout.update')
                        ->with('formMethod', 'PUT')
                        ->with('customer', $customer)
                        ->with('services', $services)
                        ->with('products', $products)
                        ->with('actInvo', 'active')
                        ->with('obj', \App\InvoiceOut::find($id));
    }

    public function update($id) {
        $service = \App\Service::find($this->request->input('idService'));
        $Invoiceout = \App\InvoiceOut::find($id);
        $Invoiceout->qty = $this->request->input('qty');
        $Invoiceout->priceWoTax = $this->request->input('priceWoTax');
        $Invoiceout->rTax = $service->rTax;
        $Invoiceout->indPaid = $this->request->input('indPaid');
        $Invoiceout->desc = $this->request->input('desc');
        $Invoiceout->idCustomer = $this->request->input('idCustomer');
        $Invoiceout->idService = $this->request->input('idService');
        $Invoiceout->save();

        // redirect
        Session::flash('message', 'Successfully updated');
        return redirect('Invoiceout');
    }

    public function show($id) {
        $Invoiceout = \App\InvoiceOut::find($id);
        $customer = \App\Customer::find($Invoiceout->idCustomer);
        $city = \App\City::find($customer->idCity);


        $items = DB::table('Invoiceoutarticle')
                ->select('Invoiceoutarticle.price','Invoiceoutarticle.qty','Invoiceoutarticle.rTax','Invoiceoutarticle.rDiscount','service.name')
                ->join('service', 'Invoiceoutarticle.idService', '=', 'service.id')
                ->where('idInvoiceOut', $id)->get();

        $data = array(
            'customer' => $customer->name, 'sInvoiceOut' => $Invoiceout->sInvoiceOut, 'sTax' => $customer->sTax,
            'address' => $customer->address, 'idCity' => $city->id, 'nameCity' => $city->name, 
            'rDiscount'=> $Invoiceout->rDiscount,'items' => $items
        );
        $pdf = PDF::loadView('pdfgen.invoiceOut0', $data);
        return $pdf->stream($Invoiceout->sInvoiceOut.'-racun.pdf');
    }
    
    public function destroy($id) {
        \App\InvoiceOutArt::where('idInvoiceOut', '=', $id)->delete();

        \App\InvoiceOut::find($id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('Invoiceout');
    }

}
