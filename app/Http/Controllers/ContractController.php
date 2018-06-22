<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;

class ContractController extends Controller {

    var $input_rules = ['name' => 'required','idCompany' => 'required'];

    public function __construct(Request $request){
        $this->middleware('auth');
        $this->request = $request;
    }
    private function insertMainSql($id = 0) {
        
        if($id>0){
            $contract = \App\Contract::find($id);
        } else {
            $contract = new \App\Contract;
        }
        
        $contract->name = $this->request->input('name');
        $contract->idCompany = $this->request->input('idCompany');
        $contract->desc = $this->request->input('desc');
        $contract->dateStart = $this->request->input('dateStart');
        $contract->dateEnd = $this->request->input('dateEnd');
        $contract->save();

        $files=$this->request->file('file');
        if($files && is_array($files)) {
            foreach ($files as $file) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $filename = substr(str_shuffle('abcefghijklmnopqrstuvwxyz1234567890'), 0, 14).'.'.$extension;
                    $file->move('upload/contract/', $filename);
                    $f = new \App\FileUpload;
                    $f->nameEnc = $filename;
                    $f->nameOrig = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
                    $f->fileExt = $extension;
                    $f->idContract = $contract->id;
                    $f->save();
            }
        }
        
        return $contract->id;
    }
    public function index() {

        $contract = DB::table('contract')
                ->select('contract.id','contract.name', 'contract.dateStart', 'contract.desc', 'contract.dateEnd', 
                        'company.name as cname')
                ->join('company', 'contract.idCompany', '=', 'company.id')
                ->orderBy('contract.dateStart','desc')->get();
        
        return view('contract.index')
                        ->with('actComp', 'active')
                        ->with('company', cisGetCompList())
                        ->with('obj', $contract);
    }
    public function create() {
        return view('contract.form')
                        ->with('actComp', 'active')
                        ->with('company', cisGetCompList())
                        ->with('formAction', 'contract.store')
                        ->with('formMethod', 'POST')
                        ->with('formTitle', 'Dodaj pogodbo')
                        ->with('obj', new \App\Contract);
    }
    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        return redirect('contract');
    }
    public function edit($id) {
        $contract = \App\Contract::find($id);
        $files = DB::table('fileupload')->where('idContract',$id)->get();

        return view('contract.form')
                        ->with('actDev', 'active')
                        ->with('company', cisGetCompList())
                        ->with('formAction', 'contract.update')
                        ->with('formMethod', 'PUT')
                        ->with('formTitle', 'Spremeni')
                        ->with('files',$files)
                        ->with('obj', $contract);
    }


    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        return redirect('contract');
    }
    public function destroy($id) {
        $cnt = DB::table('invCirc')->where('idContract',$id)->count();
        if($cnt==0) {
            DB::transaction(function() use ($id) {
                DB::table('fileupload')->where('idContract',$id)->delete();
                DB::table('contract')->where('id',$id)->delete();
            });
            Session::flash('message', 'Successfully deleted');
        } else {
            Session::flash('message-err', 'ERROR: objekt uporabljen v sistemu');
        }
        return redirect()->back()->withInput();
    }
    public function delFile($id) {
        $idContract = DB::table('fileupload')->where('id',$id)->value('idContract');
        DB::table('fileupload')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect( 'contract/'.$idContract.'/edit' );
    }


}
