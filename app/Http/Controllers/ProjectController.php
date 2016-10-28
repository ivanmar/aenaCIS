<?php namespace App\Http\Controllers;

use \DB, \Session, \Auth, \PDF;
use Illuminate\Http\Request;



class ProjectController extends Controller {

    protected $input_rules = [ 'idCustomer' => 'required','idUserRec' => 'required','nameDevice' => 'required' ];
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
 //           $project->idUser = $this->request->input('idUser');
        }
        $project->nameDevice = $this->request->input('nameDevice');
        $project->statusProject = $this->request->input('statusProject');
        $project->dateRec = $this->request->input('dateRec');
        $project->dateFin = $this->request->input('dateFin');
        $project->material = $this->request->input('material');
        $project->workDone = $this->request->input('workDone');
        $project->stateRec = $this->request->input('stateRec');
        $project->serialDevice = $this->request->input('serialDevice');
        $project->pricePredict = $this->request->input('pricePredict');
        $project->priceCharged = $this->request->input('priceCharged');
        $project->note = $this->request->input('note');
        $project->idCustomer = $this->request->input('idCustomer');
        $project->idUser = Auth::user()->id;
        $project->save();
        return $project->id;
    }

    public function index() {
        $idCust = $this->request->input('idCustomer');
        $year = $this->request->input('year');

        $q = DB::table('project')
                ->select('project.id', 'project.nameDevice', 'project.dateRec', 'project.dateFin', 'project.statusProject', 'customer.name as cname','customer.tel','customer.email')
                ->join('customer', 'project.idCustomer', '=', 'customer.id');
        if ($idCust > 0) {
            $q->where('idCustomer', $idCust);
        }
        if ($year > 0) {
            $q->where('dateRec', '>', $year . '-0-0');
            $q->where('dateRec', '<', ($year + 1) . '-0-0');
        }
        $project = $q->projectBy('project.dateRec','DESC')->get();

        return view('project.index')
                        ->with('actOrd', 'active')
                        ->with('customer', array('0' => 'izberi stranko') + DB::table('customer')->projectBy('name')->lists('name', 'id'))
                        ->with('years', array('0' => 'izberi leto') + array_combine(range(date("Y"), date("Y") - 10), range(date("Y"), date("Y") - 10)))
                        ->with('idCust', $idCust)
                        ->with('year', $year)
                        ->with('obj', $project);
    }
    public function create() {
        return view('project.form')
                        ->with('formAction', 'project.store')
                        ->with('formMethod', 'POST')
                        ->with('items', array())
                        ->with('statusProject', $this->statusProject)
                        ->with('users', DB::table('user')->lists('name', 'id'))
                        ->with('actOrd', 'active')
                        ->with('obj', new \App\Project );
    }
    public function store() {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql();
        Session::flash('message', 'Successfully created');
        return redirect('project');
        
    }

    public function edit($id) {
        return view('project.form')
                        ->with('formAction', 'project.update')
                        ->with('formMethod', 'PUT')
                        ->with('displayCancel','inline')
                        ->with('statusProject', $this->statusProject)
                        ->with('users', DB::table('user')->lists('name', 'id'))
                        ->with('actOrd', 'active')
                        ->with('obj', \App\Project::find($id));
    }
    public function update($id) {
        $this->validate($this->request, $this->input_rules);
        $this->insertMainSql($id);
        Session::flash('message', 'Successfully updated');
        return redirect('project');

    }

    public function destroy($id) {
        DB::table('project')->where('id',$id)->delete();
        Session::flash('message', 'Successfully deleted');
        return redirect('project');
    }

}
