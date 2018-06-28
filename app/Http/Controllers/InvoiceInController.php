<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class InvoiceInController extends Controller {

    var $input_rules = ['idCompany' => 'required|integer|min:1'];
    var $marketplaces = array( 'none'=>'Noben' ,'ebay'=>'ebay','aliexpress'=>'Aliexpress');
    var $paidWith = array( 'none'=>'Izberi' ,'paypal'=>'paypal','trr'=>'TRR','cash'=>'Gotovina');
    var $konto2list = array('660000'=>'Blago v lastnem skladišču','310000'=>'Zaloge surovin in materiala v skladišču');

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $invoicein = \App\InvoiceIn::find($id);
            $indEdit=true;
        } else {
            $invoicein = new \App\InvoiceIn;
            $indEdit=false;
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
        
    if($this->request->hasFile('file')) {
        $file=$this->request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = substr(str_shuffle('abcefghijklmnopqrstuvwxyz1234567890'), 0, 14).'.'.$extension;
        $file->move('public/upload/invoice/', $filename);
        $f = new \App\FileUpload;
        $f->nameEnc = $filename;
        $f->nameOrig = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
        $f->fileExt = $extension;
        $f->idInvoiceIn = $invoicein->id;
        $f->save();
    }
        if (Session::has('sessInvoIn')) {
            DB::table('invoiceInArt')->where('idInvoiceIn',$invoicein->id)->delete();
            $sessInvoIn = Session::get('sessInvoIn');
            foreach( $sessInvoIn as $key => $val ) {
                if($val['idProduct'] > 0) {
                    $invoiceinart = new \App\InvoiceInArt;
                    $invoiceinart->idProduct = $val['idProduct'];
                    $invoiceinart->qty = $val['qty'];
                    $invoiceinart->sKonto = $val['sKonto'];
                    $invoiceinart->priceUnit = cisNumFix( $val['priceUnit'] );
                    $invoiceinart->idInvoiceIn = $invoicein->id;
                    $invoiceinart->save();
                    if(! $indEdit) {
                      DB::table('product')->where('id', $val['idProduct'])->increment('stockQty', $val['qty']);
                    }
                }
           }
        }
        Session::forget('sessInvoIn');
        
        return $invoiceinart->id;
    }
    public function index() {
        $invoicein = \App\InvoiceIn::with('company')->orderBy('dateIssue','DESC')->get();
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
                        ->with('konto', $this->konto2list)
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
        $file = DB::table('fileUpload')->where('idInvoiceIn',$id)->first();
        
        $invoiceinart = DB::table('invoiceInArt')->where('idInvoiceIn',$id)->get();
        
        $j=0;
        foreach($invoiceinart as $key => $val) {
            $tmparr=array('qty'=>$val->qty, 'priceUnit'=>$val->priceUnit, 'sKonto'=>$val->sKonto, 'idProduct'=>$val->idProduct,'idArt'=>$val->id);
            Session::put('sessInvoIn', array_add($sessInvoIn = Session::get('sessInvoIn'),$j, $tmparr));
            $j++;
        }

        return view('invoiceIn.form')
                        ->with('formAction', 'invoicein.update')
                        ->with('formMethod', 'PUT')
                        ->with('customer', $customer)
                        ->with('products', $products)
                        ->with('konto', $this->konto2list)
                        ->with('file',$file)
                        ->with('marketplaces', $this->marketplaces)
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
        DB::table('invoiceInArt')->where('idInvoiceIn',$id)->delete();
        DB::table('fileUpload')->where('idInvoiceIn',$id)->delete();
        DB::table('invoiceIn')->where('id',$id)->delete();
        
        Session::flash('message', 'Successfully deleted');
        return redirect('invoicein');
    }

}
