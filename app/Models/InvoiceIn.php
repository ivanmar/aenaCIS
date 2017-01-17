<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InvoiceIn extends Eloquent
{
    protected $table = 'invoiceIn';
    public $timestamps = false;
    
    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
}
