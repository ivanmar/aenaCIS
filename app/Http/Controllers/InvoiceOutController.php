<?php namespace App\Http\Controllers;

use \DB, \Session, PDF;
use Illuminate\Http\Request;

class InvoiceOutController extends Controller {

    var $input_rules = ['idCompany' => 'required','nrInvoice' => 'required'];
    var $paymentType = array( 'none'=>'Izberi' ,'trr'=>'TRR','paypal'=>'paypal','cash'=>'Gotovina');

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $invoiceout = \App\InvoiceOut::find($id);
            $indEdit=true;
        } else {
            $invoiceout = new \App\InvoiceOut;
            $indEdit=false;
        }
        $paymentType = $this->request->input('paymentType');
        $invoiceout->paymentType = $paymentType;
        if($paymentType == 'cash') {
            $invoiceout->nrInvoice = 0;
        } else {
            $invoiceout->nrInvoice = $this->request->input('nrInvoice');
        }
        $invoiceout->idCompany = $this->request->input('idCompany');
        $invoiceout->dateIssue = $this->request->input('dateIssue');
        $invoiceout->shipCost = cisNumFix( $this->request->input('shipCost') );
        $invoiceout->desc = $this->request->input('desc');
        $invoiceout->descInternal = $this->request->input('descInternal');
        $invoiceout->save();
        
        if (Session::has('sessInvoOut')) {
            DB::table('invoiceOutArt')->where('idInvoiceOut',$invoiceout->id)->delete();
            $sessInvoOut = Session::get('sessInvoOut');
            foreach( $sessInvoOut as $key => $val ) {
                $invoiceoutart = new \App\InvoiceOutArt;
                if($val['idProduct'] > 0) {
                    $invoiceoutart->idProduct = $val['idProduct'];
                    $priceUnit = DB::table('product')->where('id',$val['idProduct'])->value('priceSelf');
                    
                } else {
                    $invoiceoutart->nameItem= $val['nameItem'];
                    $priceUnit = cisNumFix( $val['priceUnit'] );
                }
                $invoiceoutart->qty = $val['qty'];
                $invoiceoutart->priceUnit = $priceUnit;
                $invoiceoutart->idInvoiceOut = $invoiceout->id;
                $invoiceoutart->save();
                if(! $indEdit) {
                  DB::table('product')->where('id', $val['idProduct'])->decrement('stockQty', $val['qty']);
                }
           }
        }
        Session::forget('sessInvoOut');

        return $invoiceout->id;
    }
    public function index() {
        $invoiceout = \App\InvoiceOut::with('company')->get();
        Session::forget('sessInvoOut');
        Session::forget('sessProductIn');

        return view('invoiceOut.index')
                        ->with('actInvo', 'active')
                        ->with('obj', $invoiceout);
    }

    public function create() {
        $products = array('0' => 'izberi produkt') + DB::table('product')->pluck('name','id')->toArray();
        $customer = array('0' => 'končni kupec') + DB::table('company')->pluck('name','id')->toArray();
        $lastNrInvoice = DB::table('invoiceOut')->max('nrInvoice');
        return view('invoiceOut.form')
                        ->with('formAction', 'invoiceout.store')
                        ->with('formMethod', 'POST')
                        ->with('customer', $customer)
                        ->with('products', $products)
                        ->with('paymentType', $this->paymentType)
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
        $products = DB::table('product')->pluck('name','id')->toArray();
        $customer = array('0' => 'končni kupec') + DB::table('company')->pluck('name','id')->toArray();
        $invoiceoutart = DB::table('invoiceOutArt')->where('idInvoiceOut',$id)->get();
        $j=0;
        foreach($invoiceoutart as $key => $val) {
            $tmparr=array('qty'=>$val->qty, 'priceUnit'=>$val->priceUnit, 'nameItem'=>$val->nameItem, 'idProduct'=>$val->idProduct,'idArt'=>$val->id);
            Session::put('sessInvoOut', array_add($sessInvoOut = Session::get('sessInvoOut'),$j, $tmparr));
            $j++;
        }
        return view('invoiceOut.form')
                        ->with('formAction', 'invoiceout.update')
                        ->with('formMethod', 'PUT')
                        ->with('customer', $customer)
                        ->with('products', $products)
                        ->with('paymentType', $this->paymentType)
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
        if($company){
            $cName=$company->name; $cAddress=$company->address; $cDdv = $company->ddvCode; $cZip=$company->zipCode; $cCity=$company->city;
        } else {
            $cName='končni kupec'; $cAddress=''; $cDdv = ''; $cZip=''; $cCity='';
        }

        $items = DB::table('invoiceOutArt')->where('idInvoiceOut', $id)->get();

        $data = array(
            'company' => $cName, 'nrInvoice' => $invoiceout->nrInvoice,'dateIssue'=>$invoiceout->dateIssue,'dateDue'=>$invoiceout->dateDue,
            'address' => $cAddress,'ddvCode' => $cDdv, 'zipCode' => $cZip, 'city' => $cCity,'shipCost'=>$invoiceout->shipCost,
            'desc'=>$invoiceout->desc,'items' => $items
        );
        $pdf = PDF::loadView('pdfgen.invoiceOut0', $data);
        return $pdf->stream($invoiceout->nrInvoice.'-racun.pdf');
    }
    
    public function destroy($id) {
 //       $getValArt=DB::table('invoiceOutArt')->where('idInvoiceOut',$id)->get();
 //       foreach($getValArt as $valart){
 //           DB::table('productInOut')->where('idInvoiceOutArt',$valart->id)->update(['idInvoiceOutArt' => null, 'dateOut'=>null ]);
 //       }
        DB::table('invoiceOutArt')->where('idInvoiceOut',$id)->delete();
        DB::table('invoiceOut')->where('id',$id)->delete();
        
        Session::flash('message', 'Successfully deleted');
        return redirect('invoiceout');
    }

}
