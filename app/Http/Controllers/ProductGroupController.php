<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ProductGroupController extends Controller {

    var $input_rules = ['name' => 'required'];
    var $input_fields = array('name' => 'naziv');

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }

    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $productgroup = \App\ProductGroup::find($id);
        } else {
            $productgroup = new \App\ProductGroup;
        }
        $productgroup->name = $this->request->input('name');
        $productgroup->save();
        return $productgroup->id;
    }
    public function index() {
        $productgroup = \App\ProductGroup::all();
        return view('commcrud')
                        ->with('actSet', 'active')
                        ->with('formTitle', 'Vnesi')
                        ->with('objectTitle', 'productgroup')
                        ->with('fields', $this->input_fields)
                        ->with('objectlist', $productgroup)
                        ->with('formAction', 'productgroup.store')
                        ->with('formMethod', 'POST')
                        ->with('object', new \App\ProductGroup);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Narejeno');
        return redirect('productgroup');
    }

    public function edit($id) {
        $productgroup = \App\ProductGroup::all();
        return view('commcrud')
                        ->with('formTitle', 'Spremeni vnos')
                        ->with('objectTitle', 'productgroup')
                        ->with('fields',$this->input_fields)
                        ->with('objectlist', $productgroup)
                        ->with('displayCancel', 'inline')
                        ->with('formAction', 'productgroup.update')
                        ->with('formMethod', 'PUT')
                        ->with('actSet', 'active')
                        ->with('object', \App\ProductGroup::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Narejeno');
        return redirect('productgroup');
    }
    public function destroy($id) {
        $cnt=DB::table('product')->where('idProductGroup',$id)->count();
        if($cnt > 0) {
            Session::flash('message-err', 'ERR: objekt uporabljen v sistemu');
            return redirect('productgroup');
        }
        DB::table('productGroup')->where('id',$id)->delete();
        Session::flash('message', 'Narejeno');
        return redirect('productgroup');
    }

}
