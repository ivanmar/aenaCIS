<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InvoiceOutArt extends Eloquent
{
    protected $table = 'invoiceOutArt';
    public $timestamps = false;
    
    public function invoiceOut() {
        return $this->hasOne('App\InvoiceOut', 'id', 'idInvoiceOut');
    }
}
