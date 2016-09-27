<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Order extends Eloquent {

    protected $table = 'order';
    public $timestamps = false;

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'idCustomer');
    }
    public function user() {
        return $this->hasOne('App\User', 'id', 'idUser');
    }
}