<?php namespace App\Http\Controllers;

use \DB, \Auth, \Session;
use Illuminate\Http\Request;

class JsController extends Controller {

    public function __construct(Request $request){
        $this->request = $request;
    }
    
    public function index() { }
    
    public function getContactList($term) {
        $contactList = DB::table('contact')
                ->select('name as label','id as value')
                ->where('name','LIKE',$term.'%')
                ->orderBy('name')->get();
        return response()->json($contactList);
    }
    public function addContact () {
        $contact = new \App\Contact;
        $contact->name = $this->request->input('name');
        $contact->tel = $this->request->input('tel');
        $contact->email = $this->request->input('email');
        $contact->address = $this->request->input('address');
        $contact->city = $this->request->input('city');
        $contact->zipCode = $this->request->input('zipCode');
        $contact->idCompany = $this->request->input('idCompany');
        $contact->save();
        return response()->json($contact->id);
    }
    
    public function addComment () {
        $comment = new \App\Comment;
        $comment->idUser = Auth::id();
        $comment->idTicket = $this->request->input('idTicket');
        $comment->idProject = $this->request->input('idProject');
        $comment->dateTime = date("Y-m-d H:i:s");
        $comment->text = $this->request->input('name');
        $comment->save();
        return response()->json($comment->id);
    }
    public function addTask () {
        $task = new \App\Task;
        $task->idUser = Auth::id();
        $task->idTicket = $this->request->input('idTicket');
        $task->idProject = $this->request->input('idProject');
        $task->name = $this->request->input('name');
        $task->dateTimeFrom = $this->request->input('dateTimeFrom');
        $task->dateTimeTo = $this->request->input('dateTimeTo');
        $task->text = $this->request->input('text');
        $task->save();
        return response()->json($task->id);
    }
    public function addSessInvoOut () {
        $sessData=Session::get('sessInvoOut');
        $index=(is_array($sessData))?count($sessData):0;
        $idProduct = $this->request->input('idProduct');
        $nameItem = $this->request->input('nameItem');
        $priceUnit = $this->request->input('priceUnit');
        $qty = $this->request->input('qty');
        $tmparr=array('qty'=>$qty, 'priceUnit'=>$priceUnit, 'nameItem'=>$nameItem, 'idProduct'=>$idProduct);
        Session::put('sessInvoOut', array_add($sessInvoOut = Session::get('sessInvoOut'),$index, $tmparr));
    }
    public function addSessInvoIn () {
        $sessData=Session::get('sessInvoIn');
        $index=(is_array($sessData))?count($sessData):0;
        $idProduct = $this->request->input('idProduct');
        $sKonto = $this->request->input('sKonto');
        $qty = $this->request->input('qty');
        $priceUnit = $this->request->input('priceUnit');
        $tmparr=array('qty'=>$qty, 'priceUnit'=>$priceUnit, 'sKonto'=>$sKonto, 'idProduct'=>$idProduct);
        Session::put('sessInvoIn', array_add($sessInvoIn = Session::get('sessInvoIn'),$index, $tmparr));
    }
    public function delSessInvoOut () {
        $index = $this->request->input('index');
        $sessData=Session::get('sessInvoOut');
        if(isset($sessData[$index]['idArt'])) {
            DB::table('invoiceOutArt')->where('id',$sessData[$index]['idArt'])->delete();
        }
        unset($sessData[$index]);
        $newSessData = array_values($sessData);
        Session::put('sessInvoOut', $newSessData);
    }
    public function delSessInvoIn () {
        $index = $this->request->input('index');
        $sessData=Session::get('sessInvoIn');
        if(isset($sessData[$index]['idArt'])) {
            DB::table('invoiceInArt')->where('id',$sessData[$index]['idArt'])->delete();
        }
        unset($sessData[$index]);
        $newSessData = array_values($sessData);
        Session::put('sessInvoIn', $newSessData);
    }

}
