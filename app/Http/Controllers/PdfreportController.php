<?php namespace App\Http\Controllers;

use \DB, PDF;
use Illuminate\Http\Request;

class PdfreportController extends Controller {

    public function __construct(Request $request){
        $this->request = $request;
    }
    
    public function pdfShow($typeMeasure, $id) {
        if($typeMeasure == 'elec') {
            $data = $this->pdfElec($id);
            $pdf = PDF::loadView('pdfgen.melectric0', $data);
        }elseif($typeMeasure == 'elpr') {
            $data = $this->pdfElpr($id);
            $pdf = PDF::loadView('pdfgen.melprotect0', $data);
        }elseif($typeMeasure == 'envi') {
            $data = $this->pdfEnvi($id);
            $pdf = PDF::loadView('pdfgen.menv0', $data);
        }elseif($typeMeasure == 'nois') {
            $data = $this->pdfNois($id);
            $pdf = PDF::loadView('pdfgen.mnoise0', $data);
        }elseif($typeMeasure == 'devi') {
            $data = $this->pdfDevi($id);
            $pdf = PDF::loadView('pdfgen.mdevice0', $data);
        } else {
            return 0;
        }
        return $pdf->download($typeMeasure .'_' .$id . '_meritev.pdf');
    }
    private function getCustInfo($id) {
        $custinfo = DB::table('customer')->select('customer.id','customer.name','custlocation.idCity',
                                                  'custlocation.address','city.name as cityname')
                    ->join('custlocation','custlocation.idCustomer','=','customer.id')
                    ->join('city','custlocation.idCity','=','city.id')
                    ->where('customer.id',$id)
                    ->where('custlocation.indMain',1)->first();
        return $custinfo;
    }
    private function getLocInfo($id) {
        $locinfo = DB::table('custlocation')->select('custlocation.name','custlocation.idCity','custlocation.address','city.name as cityname')
                    ->join('city','city.id','=','custlocation.idCity')
                    ->where('custlocation.id',$id)->first();
        return $locinfo;
    }
    private function createDataArr($id) {
        $measure = \App\Measure::find($id);
        $customer = $this->getCustInfo($measure->idCustomer);
        $custlocation = $this->getLocInfo($measure->idCustlocation);
        $dateRep = (empty($measure->dateRep)) ? date('Y-m-d') : $measure->dateRep;
        
        $data = array(
            'customer' => $customer->name, 'idCustomer' => $customer->id, 
            'custAddr' => $customer->address, 'custCity' => $customer->idCity.' '.$customer->cityname,
            'mntValid'=>$measure->mntValid,'dateTest' => $measure->dateTest, 
            'dateRep' => $dateRep,'dateLast' => $measure->dateLast,
            'objName' => $custlocation->name,'idsInstrument' => $measure->idsInstrument, 
            'objAddr' => $custlocation->address,'objCity' => $custlocation->idCity.' '.$custlocation->cityname, 
            'desc' => $measure->desc,'result' => $measure->result,
            'sReport' => date('Y', strtotime($dateRep)).'/SLO/'. $measure->id
        );
        return $data;
    }
    private function getInstArr($idsInst) {
        $instrument = array();
        $instTmpArr = explode('|',trim($idsInst));
        foreach($instTmpArr as $val) {
            if($val > 0) {
                $inst = \App\Instrument::find($val);
                $instrument[$inst->name]=$inst->cert;
            }
        }
        return $instrument;
    }

    private function pdfElec($id) {
        $mTitle = 'ZAPISNIK O MERJENJU OZEMLJITVENE UPORNOSTI IZKLOPILNEGA TOKOKROGA in NAPETOSTI DOTIKA';
        $measure = \App\Measure::find($id);
        $chkV = DB::table('melectricchk')->where('typeChk', 'visual')->get();
        $chkE = DB::table('melectricchk')->where('typeChk', 'electric')->get();
        $idsChk = array();
        $idsChktmp = explode("|", $measure->idsElectricChk);
        foreach ($idsChktmp as $cval) {
            if (is_numeric($cval) && $cval > 0) {
                $idsChk[] = $cval;
            }
        }
        $objects = DB::table('melectricobj')->where('idMeasure', $id)->get();
        $objval = array();
        for ($i = 0; $i < count($objects); $i++) {
            $objval[$objects[$i]->id] = DB::table('melectricval')->where('idMelectricobj', $objects[$i]->id)->get();
        }

        $data = $this->createDataArr($id) +  array('instrument' => $this->getInstArr($measure->idsInstrument),
            'objects' => $objects, 'objval' => $objval, 
            'provider' => $measure->elProvider, 'typeEl' => $measure->elType, 'typeElGrnd' => $measure->elTypeGrnd,
            'mTitle' => $mTitle, 'chkV' => $chkV, 'chkE' => $chkE, 'idsChk' => $idsChk);
        return $data;
    }
    private function pdfElpr($id) {
        $mTitle='POROČILO O MERITVAH UPORNOSTI OZEMLJILA STRELOVODNE NAPELJAVE';
        $measure = \App\Measure::find($id);

        $objVal=\App\Melprotectval::where('idMeasure', $id)->get();
        $data = $this->createDataArr($id) + array('instrument' => $this->getInstArr($measure->idsInstrument),
            'objVal' => $objVal,'mTitle' => $mTitle,'elprImage' => $measure->elprImage);
        return $data;
    }
    private function pdfEnvi($id) {
        $mTitle='ZAPISNIK O PREISKAVAH DELOVNEGA OKOLJA';
        $measure = \App\Measure::find($id);
        $objVal = DB::table('menvval')
                        ->select('menvval.name', 'mcT110', 'mcT01', 'mcVA','mcRelVlaga', 'mcMet','mcIclo',
                                'mcSO', 'ilEDN','ilEKOMB', 'ilSvVir', 'ilSO')
                        ->where('idMeasure', $id)->get();
        $data = $this->createDataArr($id) + array(
            'envRelVlaga' => $measure->envRelVlaga, 'envTemp' => $measure->envTemp,
            'envLight' => $measure->envLight, 'objVal'=>$objVal,'mTitle'=>$mTitle);
        return $data;
    }
    
    private function pdfNois($id) {
        $mTitle='ZAPISNIK O PREIZKAVAH HRUPA V DELOVNEM OKOLJU';
        $measure = \App\Measure::find($id);

        $groupList = array();
        $objVal=DB::table('mnoiseval')->where('idMeasure',$id)->orderBy('sGroup')->get();
        foreach ($objVal as $val) {
            $groupList[$val->sGroup] = $val->nameGroup;
        }
        $data = $this->createDataArr($id) + array(
            'instrument'=>$this->getInstArr($measure->idsInstrument),
            'groupList'=>$groupList,'objVal'=>$objVal,'mTitle'=> $mTitle);
        return $data;
    }
    private function pdfDevi($id) {
        $mTitle='POROČILO O PREGLEDU IN PREIZKUSU DELOVNE OPREME';
        $objVal=\App\Mdeviceobj::where('idMeasure', $id)->get();
        $data = $this->createDataArr($id) + array('objVal' => $objVal,'mTitle' => $mTitle);
        return $data;

    }
}
