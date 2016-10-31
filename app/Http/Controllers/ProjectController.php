<?php namespace App\Http\Controllers;

use \DB, \Session, \Auth;
use Illuminate\Http\Request;



class ProjectController extends Controller {

    protected $input_rules = [ 'idCompany' => 'required','pname' => 'required' ];
    protected $statusProject = array('naroceno' => 'Naročeno', 'preklic' => 'Preklicano', 'vteku' => 'V teku', 'narejeno' => 'Narejeno');

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
    }

    private function insertMainSql($id = 0) {

        if ($id > 0) {
            $project = \App\Project::find($id);
        } else {
            $project = new \App\Project;
        }
        $project->name = $this->request->input('pname');
        $project->idCompany = $this->request->input('idCompany');
        $project->idUser = Auth::user()->id;
        $project->status = $this->request->input('status');
        $project->dateOpen = $this->request->input('dateOpen');
        $project->dateClose = $this->request->input('dateClose');
        $project->desc = $this->request->input('desc');
        $project->note = $this->request->input('note');
        $project->save();
        return $project->id;
    }

    public function index() {
        $idComp = $this->request->input('idCompany');
        $year = $this->request->input('year');

        $q = DB::table('project')
                ->select('project.id', 'project.name', 'project.dateOpen', 'project.dateClose', 'project.status', 'company.name as cname')
                ->join('company', 'project.idCompany', '=', 'company.id');
        if ($idComp > 0) {
            $q->where('idCompany', $idComp);
        }
        if ($year > 0) {
            $q->where('dateOpen', '>', $year . '-0-0');
            $q->where('dateOpen', '<', ($year + 1) . '-0-0');
        }
        $project = $q->orderBy('project.dateOpen','DESC')->get();

        return view('project.index')
                        ->with('actProj', 'active')
                        ->with('company', array('0' => 'izberi podjetje') + DB::table('company')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->with('years', array('0' => 'izberi leto') + array_combine(range(date("Y"), date("Y") - 10), range(date("Y"), date("Y") - 10)))
                        ->with('idComp', $idComp)
                        ->with('year', $year)
                        ->with('obj', $project);
    }
    public function create() {
        $compList = array(''=>'podjetje') + DB::table('company')->pluck('name','id')->toArray();
        return view('project.form')
                        ->with('formAction', 'project.store')
                        ->with('formMethod', 'POST')
                        ->with('status', $this->statusProject)
                        ->with('compList',$compList)
                        ->with('actProj', 'active')
                        ->with('obj', new \App\Project );
    }
    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('project');
        
    }

    public function edit($id) {
        $compList = array(''=>'podjetje') + DB::table('company')->pluck('name','id')->toArray();
        return view('project.form')
                        ->with('formAction', 'project.update')
                        ->with('formMethod', 'PUT')
                        ->with('displayCancel','inline')
                        ->with('status', $this->statusProject)
                        ->with('compList',$compList)
                        ->with('actProj', 'active')
                        ->with('idProject',$id)
                        ->with('obj', \App\Project::find($id));
    }
    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('project');

    }

    public function destroy($id) {
        DB::table('task')->where('idProject',$id)->delete();
        DB::table('project')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('project');
    }

}
