<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InvoiceOut extends Eloquent
{
    protected $table = 'invoiceOut';
    public $timestamps = false;
    
    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
    public function contact() {
        return $this->hasOne('App\Contact', 'id', 'idContact');
    }
}
