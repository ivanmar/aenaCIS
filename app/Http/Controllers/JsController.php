<?php namespace App\Http\Controllers;

use \DB, \Auth;
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

}
