<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Ticket extends Eloquent {

    protected $table = 'ticket';
    public $timestamps = false;

    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
    public function contact() {
        return $this->hasOne('App\Contact', 'id', 'idContact');
    }
    public function user() {
        return $this->hasOne('App\User', 'id', 'idUser');
    }
}