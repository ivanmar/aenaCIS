<?php namespace App\Http\Controllers;

use \DB;


class DashboardController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }
    private function is_date( $str ) {
          $stamp = strtotime( $str );
          if (!is_numeric($stamp)) {
             return FALSE;
          }
          $month = date( 'm', $stamp );
          $day   = date( 'd', $stamp );
          $year  = date( 'Y', $stamp );
          return checkdate($month, $day, $year);
    }
    
    public function index() {
        
        $dateToday = date('Y-m-d');
        $timeToday =  strtotime($dateToday);
        
        
 // MESURE       
        $statusMes=array();
        $dateSqlShow = date('Y-m-d',strtotime(date('Y-m-d').' +1 Month'));

        $objTable = DB::table('measure')
                ->select('dateTest','mntValid','typeMeasure','measure.id','measure.desc','customer.name as cname')
                ->join('customer','idCustomer','=','customer.id')
                ->where('dateTest','>',0)
                ->where('mntValid','>',0)
                ->whereRaw("date (dateTest, '+' || mntValid || ' months') < ?", array($dateSqlShow))
                ->take(20)->orderBy('dateTest','desc')->get();

        foreach ($objTable as $mkey => $mval) {
            $timeTestMnt = strtotime($mval->dateTest.' +'.$mval->mntValid.' Month');
            $timeTestLimit = mktime(0,0,0,date("m")+1,date("d"),date("Y"));
            if($timeTestMnt < $timeToday) {
                $statusMes[$mkey][$mval->id]='red';
            } else {
                $statusMes[$mkey][$mval->id]='orange';
            }
        }
        return view('dashboard')
                        ->with('actHom', 'active')
                        ->with('objTable', $objTable)
                        ->with('statusMes', $statusMes);
    }

}
