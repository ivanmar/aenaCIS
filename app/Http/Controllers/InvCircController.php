<?php

namespace App\Http\Controllers;

use \DB,\Session;
use Illuminate\Http\Request;

class InvCircController extends Controller {

    var $input_rules = ['dateStart' => 'required', 'name' => 'required', 'circSyntax' => 'required'];

    public function __construct(Request $request) {
        $this->middleware('auth');
        $this->request = $request;
    }

    private function insertMainSql($id = 0) {

        if ($id > 0) {
            $invcirc = \App\InvCirc::find($id);
        } else {
            $invcirc = new \App\InvCirc;
        }
        $invcirc->dateStart = $this->request->input('dateStart');
        $invcirc->name = $this->request->input('name');
        $invcirc->idContract = $this->request->input('idContract');
        $invcirc->circSyntax = $this->request->input('circSyntax');
        $invcirc->desc = $this->request->input('desc');
        $invcirc->save();
        return $invcirc->id;
    }

    public function index() {
        $contracts = array('0' => 'izberi pogodbo') + DB::table('contract')->pluck('name', 'id')->toArray();
        return view('invcirc')
                        ->with('formAction', 'invcirc.store')
                        ->with('formMethod', 'POST')
                        ->with('objectlist', \App\InvCirc::all())
                        ->with('contracts', $contracts)
                        ->with('actInvo', 'active')
                        ->with('obj', new \App\InvCirc);
    }

    public function create() {
        $contracts = array('0' => 'izberi pogodbo') + DB::table('contract')->pluck('name', 'id')->toArray();

        return view('invcirc')
                        ->with('formAction', 'invcirc.store')
                        ->with('formMethod', 'POST')
                        ->with('objectlist', \App\InvCirc::all())
                        ->with('contracts', $contracts)
                        ->with('actInvo', 'active')
                        ->with('obj', new \App\InvCirc);
    }

    public function store() {
        $this->validate($this->request, $this->input_rules);
        if ($this->insertMainSql()) {
            Session::flash('message', 'Successfully created');
        } else {
            Session::flash('message-err', 'ERROR: Could not find Invoice number');
        }
        return redirect('invcirc');
    }

    public function edit($id) {
        $contracts = array('0' => 'izberi pogodbo') + DB::table('contract')->pluck('name', 'id')->toArray();
        $invcirc = \App\InvCirc::find($id);

        return view('invcirc')
                        ->with('formAction', 'invcirc.update')
                        ->with('formMethod', 'PUT')
                        ->with('objectlist', \App\InvCirc::all())
                        ->with('contracts', $contracts)
                        ->with('actInvo', 'active')
                        ->with('obj', $invcirc);
    }

    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('invcirc');
    }

    public function destroy($id) {
        DB::table('invcirc')->where('id', $id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('invcirc');
    }

}
