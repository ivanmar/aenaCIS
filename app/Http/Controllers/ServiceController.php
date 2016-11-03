<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ServiceController extends Controller {

    var $input_rules = ['name' => 'required', 'priceUnit' => 'required|numeric'];
    var $input_fields = array('name' => 'naziv', 'priceUnit' => 'cena', 'ME'=>array('kom'=>'kom','ura'=>'ura'));

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }

    private function insertMainSql($id = 0) {
        if ($id > 0) {
            $service = \App\Service::find($id);
        } else {
            $service = new \App\Service;
        }
        $service->name = $this->request->input('name');
        $service->priceUnit = $this->request->input('priceUnit');
        $service->ME = $this->request->input('ME');
        $service->save();
        return $service->id;
    }
    public function index() {
        $service = \App\Service::all();
        return view('commcrud')
                        ->with('actSet', 'active')
                        ->with('formTitle', 'Vnesi storitev')
                        ->with('objectTitle', 'service')
                        ->with('fields', $this->input_fields)
                        ->with('objectlist', $service)
                        ->with('formAction', 'service.store')
                        ->with('formMethod', 'POST')
                        ->with('object', new \App\Service);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Narejeno');
        return redirect('service');
    }

    public function edit($id) {
        $service = \App\Service::all();
        return view('commcrud')
                        ->with('formTitle', 'Spremeni vnos')
                        ->with('objectTitle', 'service')
                        ->with('fields',$this->input_fields)
                        ->with('objectlist', $service)
                        ->with('displayCancel', 'inline')
                        ->with('formAction', 'service.update')
                        ->with('formMethod', 'PUT')
                        ->with('actSet', 'active')
                        ->with('object', \App\Service::find($id));
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Narejeno');
        return redirect('service');
    }
    public function destroy($id) {
        DB::table('service')->where('id',$id)->delete();
        Session::flash('message', 'Narejeno');
        return redirect('service');
    }

}
