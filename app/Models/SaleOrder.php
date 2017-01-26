<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SaleOrder extends Eloquent
{
    protected $table = 'saleOrder';
    public $timestamps = false;
    
    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
    public function contact() {
        return $this->hasOne('App\Contact', 'id', 'idContact');
    }
}
