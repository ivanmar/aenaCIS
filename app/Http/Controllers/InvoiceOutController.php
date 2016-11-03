<?php namespace App\Http\Controllers;

use \DB, \Session, PDF;
use Illuminate\Http\Request;

class InvoiceOutController extends Controller {

    var $input_rules = ['idCustomer' => 'required|integer|min:10'];

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    
    public function index() {
        $Invoiceout = \App\InvoiceOut::with('customer')->get();

        return view('Invoiceout.index')
                        ->with('actCus', 'active')
                        ->with('Invoiceout', $Invoiceout);
    }

    public function create() {

        $service = array('0' => 'izberi storitev') + Service::lists('name', 'id')->all();
        return view('Invoiceout.form')
                        ->with('formAction', 'Invoiceout.store')
                        ->with('formMethod', 'POST')
                        ->with('customer', ovrGetCustList())
                        ->with('service', $service)
                        ->with('actCus', 'active')
                        ->with('Invoiceout', new \App\InvoiceOut);
    }

    public function store() {

        $this->validate($this->request, $this->input_rules);

        $Invoiceout = new \App\InvoiceOut;
        $Invoiceout->sInvoiceOut = 'R' . date('Ymd-His');
        $Invoiceout->desc = $this->request->input('desc');
        $Invoiceout->rDiscount = $this->request->input('rDiscount');
        $Invoiceout->idCustomer = $this->request->input('idCustomer');
        $Invoiceout->save();

        $idInvoiceOut = $Invoiceout->id;
        $items = $this->request->input('idService');
        $qtys = $this->request->input('qty');
        $disSingle = $this->request->input('rDisSingle');
        for ($i = 0; $i < count($items); $i++) {
            if (!empty($items[$i]) && $items[$i] > 0) {
                $service = Service::find($items[$i]);

                $Invoiceoutart = new \App\InvoiceOutArt;
                $Invoiceoutart->idInvoiceOut = $idInvoiceOut;
                $Invoiceoutart->idService = $items[$i];
                $Invoiceoutart->price = $service->price;
                $Invoiceoutart->rTax = $service->rTax;
                $Invoiceoutart->qty = $qtys[$i];
                $Invoiceoutart->rDiscount = $disSingle[$i];
                $Invoiceoutart->save();
            }
        // redirect
        Session::flash('message', 'Successfully created');
        return redirect('Invoiceout');
        }
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $customer = \App\Customer::lists('name', 'id');
        $service = \App\Service::lists('name', 'id');
        return view('Invoiceout.form')
                        ->with('formAction', 'Invoiceout.update')
                        ->with('formMethod', 'PUT')
                        ->with('customer', $customer)
                        ->with('service', $service)
                        ->with('actCus', 'active')
                        ->with('Invoiceout', \App\InvoiceOut::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $this->validate($this->request, $this->input_rules);
            // store
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        \App\InvoiceOutArt::where('idInvoiceOut', '=', $id)->delete();

        \App\InvoiceOut::find($id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('Invoiceout');
    }

}
