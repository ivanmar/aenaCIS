<?php namespace App\Http\Controllers;

use \DB;


class DashboardController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index() {
        $dataInv = array();
        
        $dt = date("Y-m-d");
        $dateShow = date( "Y-m-d", strtotime( "$dt +3 day" ) );
        
        $circ = DB::table('invCirc')->get();
        foreach($circ as $key => $val) {
            $q = DB::table('invoiceOut')->select('invoiceOut.id','dateIssue','company.name as cname','invCirc.name as circname','circSyntax')
                    ->leftJoin('company','idCompany','=','company.id')
                    ->join('invCirc','idInvCirc','=','invCirc.id')
                    ->where('idInvCirc',$val->id)
                    ->orderBy('invoiceOut.dateIssue', 'desc')->first();
            
            if(isset($q->id)) {
                $dateCirc = date("Y-m-d",strtotime($q->dateIssue. '+'.$val->circSyntax)); 
                if(strtotime($dateCirc) <= strtotime($dateShow)) {
                    $dataInv[] = $q;
                }
            }
            
        }


        return view('dashboard')
                        ->with('dataInv', $dataInv);
    }

}
