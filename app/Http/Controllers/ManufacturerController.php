<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ManufacturerController extends Controller {

    var $input_rules = ['name' => 'required'];
    var $input_fields = array('name' => 'naziv','manCode' => 'Koda proizvajalca');

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }

    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $manufact = \App\Manufacturer::find($id);
        } else {
            $manufact  = new \App\Manufacturer;
        }
        $manufact ->name = $this->request->input('name');
        $manufact ->manCode = $this->request->input('manCode');
        $manufact ->save();
        return $manufact ->id;
    }
    public function index() {
        $manufact = \App\Manufacturer::all();
        return view('commcrud')
                        ->with('actSet', 'active')
                        ->with('formTitle', 'Vnesi')
                        ->with('objectTitle', 'manufacturer')
                        ->with('fields', $this->input_fields)
                        ->with('objectlist', $manufact)
                        ->with('formAction', 'manufacturer.store')
                        ->with('formMethod', 'POST')
                        ->with('object', new \App\Manufacturer);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Narejeno');
        return redirect('manufacturer');
    }

    public function edit($id) {
        $manufact = \App\Maufacturer::all();
        return view('commcrud')
                        ->with('formTitle', 'Spremeni vnos')
                        ->with('objectTitle', 'manufacturer')
                        ->with('fields',$this->input_fields)
                        ->with('objectlist', $manufact)
                        ->with('displayCancel', 'inline')
                        ->with('formAction', 'maufacturer.update')
                        ->with('formMethod', 'PUT')
                        ->with('actSet', 'active')
                        ->with('object', \App\Maufacturer::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Narejeno');
        return redirect('maufacturer');
    }
    public function destroy($id) {
        $cnt=DB::table('product')->where('idManufacturer',$id)->count();
        if($cnt > 0) {
            Session::flash('message-err', 'ERR: objekt uporabljen v sistemu');
            return redirect('maufacturer');
        }
        DB::table('maufacturer')->where('id',$id)->delete();
        Session::flash('message', 'Narejeno');
        return redirect('maufacturer');
    }

}
