<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Storno extends Eloquent
{
    protected $table = 'storno';
    public $timestamps = false;
    
    public function invoiceout() {
        return $this->hasOne('App\InvoiceOut', 'id', 'idInvoiceOut');
    }
}
