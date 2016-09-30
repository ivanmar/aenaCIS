<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Event extends Eloquent {

    protected $table = 'event';
    public $timestamps = false;

    public function project() {
        return $this->hasOne('App\Project', 'id', 'idProject');
    }
    public function ticket() {
        return $this->hasOne('App\Ticket', 'id', 'idTicket');
    }
    public function user() {
        return $this->hasOne('App\User', 'id', 'idUser');
    }
}