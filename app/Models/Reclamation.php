<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Reclamation extends Eloquent
{
    protected $table = 'reclamation';
    public $timestamps = false;
    
    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
    public function contact() {
        return $this->hasOne('App\Contact', 'id', 'idContact');
    }
    public function product() {
        return $this->hasOne('App\Product', 'id', 'idProduct');
    }
}
