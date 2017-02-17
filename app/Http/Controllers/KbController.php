<?php namespace App\Http\Controllers;

use \DB, \Session;
use Illuminate\Http\Request;



class KbController extends Controller {

    protected $input_rules = [ 'name' => 'required' ];

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
    }

    private function insertMainSql($id = 0) {

        if ($id > 0) {
            $kb = \App\Kb::find($id);
        } else {
            $kb = new \App\Kb;
        }
        
        $kb->name = $this->request->input('name');
        $kb->idCompany = $this->request->input('idCompany');
        $kb->idProject = $this->request->input('idProject');
        $kb->text = $this->request->input('text');
        $kb->save();
        
        $files=$this->request->file('file');
        foreach ($files as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = substr(str_shuffle('abcefghijklmnopqrstuvwxyz1234567890'), 0, 14).'.'.$extension;
                $file->move('public/upload/kb/', $filename);
                $f = new \App\FileUpload;
                $f->nameEnc = $filename;
                $f->nameOrig = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
                $f->fileExt = $extension;
                $f->idKb = $kb->id;
                $f->save();
        }
        return $kb->id;
    }

    public function index() {
        $idComp = $this->request->input('idCompany');
        $idProj = $this->request->input('idProject');

        $q = DB::table('kb')
                ->select('kb.id', 'kb.name', 'kb.text', 'company.name as cname', 'project.name as pname')
                ->leftJoin('company', 'kb.idCompany', '=', 'company.id')
                ->leftJoin('project', 'kb.idProject', '=', 'project.id');
        if ($idComp > 0) {
            $q->where('kb.idCompany', $idComp);
        }
        if ($idProj > 0) {
            $q->where('kb.idProject', $idProj);
        }
        $kb = $q->get();

        return view('kb.index')
                        ->with('actKb', 'active')
                        ->with('company', array('0' => 'izberi podjetje') + DB::table('company')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('project', array('0' => 'izberi projekt') + DB::table('project')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('idComp', $idComp)
                        ->with('idProj', $idProj)
                        ->with('obj', $kb);
    }
    public function create() {
        $compList = array(''=>'podjetje') + DB::table('company')->pluck('name','id')->toArray();
        $projList = array(''=>'projekt') + DB::table('project')->pluck('name','id')->toArray();
        return view('kb.form')
                        ->with('formAction', 'kb.store')
                        ->with('formMethod', 'POST')
                        ->with('compList',$compList)
                        ->with('projList',$projList)
                        ->with('actKb', 'active')
                        ->with('obj', new \App\Kb);
    }
    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('kb');
        
    }

    public function edit($id) {
        $compList = array(''=>'podjetje') + DB::table('company')->pluck('name','id')->toArray();
        $projList = array(''=>'projekt') + DB::table('project')->pluck('name','id')->toArray();
        $files = DB::table('fileUpload')->where('idKb',$id)->get();
        return view('kb.form')
                        ->with('formAction', 'kb.update')
                        ->with('formMethod', 'PUT')
                        ->with('displayCancel','inline')
                        ->with('compList',$compList)
                        ->with('projList',$projList)
                        ->with('files',$files)
                        ->with('actKb', 'active')
                        ->with('obj', \App\Kb::find($id));
    }
    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('kb');

    }

    public function destroy($id) {
        DB::table('fileUpload')->where('idKb',$id)->delete();
        DB::table('kb')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('kb');
    }

}
