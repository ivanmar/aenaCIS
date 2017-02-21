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
        $product->idManufacturer = $this->request->input('idManufacturer');
        $product->idProductGroup = $this->request->input('idProductGroup');
        $product->codeManufact = $this->request->input('codeManufact');
        $product->codeSelf = $this->request->input('codeSelf');
        $product->priceSelf = $this->request->input('priceSelf');
        $product->oemCodes = $this->request->input('oemCodes');
        $product->note = $this->request->input('note');
        $product->save();
        
        if($this->request->hasFile('image')) {
            $image=$this->request->file('image');
            $extension = strtolower($image->getClientOriginalExtension());
            $filename = substr(str_shuffle('abcefghijklmnopqrstuvwxyz1234567890'), 0, 14).'.'.$extension;
            $image->move('public/upload/product/', $filename);
            $f = new \App\FileUpload;
            $f->nameEnc = $filename;
            $f->nameOrig = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension());
            $f->fileExt = $extension;
            $f->idProduct = $product->id;
            $f->save();
        }
        
        return $product->id;
    }

    public function index() {
        $idManufact = $this->request->input('idManufact');
        $idProG = $this->request->input('idProductGroup');

        $q = DB::table('product')
                ->select('product.id', 'product.name', 'product.priceSelf', 'product.codeManufact','product.codeSelf','product.note',
                         'manufacturer.name as mname','productgroup.name as gname')
                ->leftJoin('manufacturer', 'product.idManufacturer', '=', 'manufacturer.id')
                ->leftJoin('productgroup', 'product.idProductGroup', '=', 'productgroup.id');
        if ($idManufact > 0) {
            $q->where('product.idManufacturer', $idManufact);
        }
        if ($idProG > 0) {
            $q->where('product.idProductGroup', $idProG);
        }
        $product = $q->paginate(40);

        return view('product.index')
                        ->with('actProd', 'active')
                        ->with('manufacturer', array('0' => 'izberi proizvajalca') + DB::table('manufacturer')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('productgroup', array('0' => 'izberi skupino') + DB::table('productGroup')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('idManufact', $idManufact)
                        ->with('idProG', $idProG)
                        ->with('obj', $product);
    }
    public function create() {
        $manuList = array(''=>'izberi') + DB::table('manufacturer')->pluck('name','id')->toArray();
        $grList = array(''=>'grupe') + DB::table('productGroup')->pluck('name', 'id')->toArray();
        return view('product.form')
                        ->with('formAction', 'product.store')
                        ->with('formMethod', 'POST')
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
        $manuList = array(''=>'izberi') + DB::table('manufacturer')->pluck('name','id')->toArray();
        $grList = array(''=>'grupe') + DB::table('productGroup')->pluck('name', 'id')->toArray();
        $image = DB::table('fileUpload')->where('idProduct', $id)->first();
        return view('product.form')
                        ->with('formAction', 'product.update')
                        ->with('formMethod', 'PUT')
                        ->with('displayCancel','inline')
                        ->with('manuList',$manuList)
                        ->with('image',$image)
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
        DB::table('fileUpload')->where('idProduct',$id)->delete();
        DB::table('product')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('product');
    }

}
