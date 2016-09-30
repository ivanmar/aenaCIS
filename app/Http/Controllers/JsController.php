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
        $contact->idCompany = $this->request->input('idCompany');
        $contact->ddvCompany = $this->request->input('ddvCompany');
        $contact->save();
        return response()->json($contact->id);
    }
    
    public function addComment () {
        $comment = new \App\Comment;
        $comment->idUser = Auth::id();
        $comment->idTicket = $this->request->input('idTicket');
        $comment->idProject = $this->request->input('idProject');
        $comment->dateTime = date("Y-m-d H:i:s");
        $comment->text = $this->request->input('text');
        $comment->save();
        return response()->json($comment->id);
        
    }
    public function addEvent () {
        $event = new \App\Event;
        $event->idUser = Auth::id();
        $event->idTicket = $this->request->input('idTicket');
        $event->idProject = $this->request->input('idProject');
        $event->name = $this->request->input('name');
        $event->dateTimeFrom = $this->request->input('dateTimeFrom');
        $event->dateTimeTo = $this->request->input('dateTimeTo2');
        $event->text = $this->request->input('text');
        $event->save();
        return response()->json($event->id);
        
    }

}
