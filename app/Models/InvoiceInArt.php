<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InvoiceInArt extends Eloquent
{
    protected $table = 'invoiceInArt';
    public $timestamps = false;
    
    public function invoiceOut() {
        return $this->hasOne('App\invoiceIn', 'id', 'idInvoiceIn');
    }
}
