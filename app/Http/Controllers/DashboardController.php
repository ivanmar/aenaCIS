<?php namespace App\Http\Controllers;

use \DB;


class DashboardController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index() {
        
        $dateToday = date('Y-m-d');
        $timeToday =  strtotime($dateToday);
        
        
 // MESURE       
        $statusMes=array();
        $dateSqlShow = date('Y-m-d',strtotime(date('Y-m-d').' +1 Month'));

        return view('dashboard')
                        ->with('statusMes', null);
    }

}
