<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ProductController extends Controller {

    protected $input_rules = [ 'name' => 'required' ];

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
    }
    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $product = \App\Product::find($id);
        } else {
            $product = new \App\Product;
        }
        $product->name = $this->request->input('name');
        $product->idCompany = $this->request->input('idCompany');
        $product->idManufacturer = $this->request->input('idManufacturer');
        $product->idProductGroup = $this->request->input('idProductGroup');
        $product->codeVendor = $this->request->input('codeVendor');
        $product->priceVendor = $this->request->input('priceVendor');
        $product->codeSelf = $this->request->input('codeSelf');
        $product->priceSelf = $this->request->input('priceSelf');
        $product->oemCodes = $this->request->input('oemCodes');
        $product->note = $this->request->input('note');
        $product->save();
        return $product->id;
    }

    public function index() {
        $idComp = $this->request->input('idCompany');
        $idProG = $this->request->input('idProductGroup');

        $q = DB::table('product')
                ->select('product.id', 'product.name', 'product.priceSelf', 'product.priceVendor','product.codeVendor','product.codeSelf','product.note',
                        'company.name as cname', 'productgroup.name as gname')
                ->leftJoin('company', 'product.idCompany', '=', 'company.id')
                ->leftJoin('productgroup', 'product.idProductGroup', '=', 'productgroup.id');
        if ($idComp > 0) {
            $q->where('product.idCompany', $idComp);
        }
        if ($idProG > 0) {
            $q->where('product.idProductGroup', $idProG);
        }
        $product = $q->paginate(40);

        return view('product.index')
                        ->with('actProd', 'active')
                        ->with('company', array('0' => 'izberi podjetje') + DB::table('company')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('productgroup', array('0' => 'izberi grupo') + DB::table('productGroup')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('idComp', $idComp)
                        ->with('idProG', $idProG)
                        ->with('obj', $product);
    }
    public function create() {
        $compList = array(''=>'izberi') + DB::table('company')->where('indVendor',1)->pluck('name','id')->toArray();
        $manuList = array(''=>'izberi') + DB::table('manufacturer')->pluck('name','id')->toArray();
        $grList = array(''=>'grupe') + DB::table('productGroup')->pluck('name', 'id')->toArray();
        return view('product.form')
                        ->with('formAction', 'product.store')
                        ->with('formMethod', 'POST')
                        ->with('compList',$compList)
                        ->with('manuList',$manuList)
                        ->with('grList',$grList)
                        ->with('actProd', 'active')
                        ->with('obj', new \App\Product);
    }
    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('product');
        
    }

    public function edit($id) {
        $compList = array(''=>'izberi') + DB::table('company')->pluck('name','id')->toArray();
        $manuList = array(''=>'izberi') + DB::table('manufacturer')->pluck('name','id')->toArray();
        $grList = array(''=>'grupe') + DB::table('productGroup')->pluck('name', 'id')->toArray();
        return view('product.form')
                        ->with('formAction', 'product.update')
                        ->with('formMethod', 'PUT')
                        ->with('displayCancel','inline')
                        ->with('compList',$compList)
                        ->with('manuList',$manuList)
                        ->with('grList',$grList)
                        ->with('actProduct', 'active')
                        ->with('obj', \App\Product::find($id));
    }
    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('product');
    }
    public function destroy($id) {
        DB::table('product')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('product');
    }

}
