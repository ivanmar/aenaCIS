<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SaleOrderArt extends Eloquent
{
    protected $table = 'saleOrderArt';
    public $timestamps = false;
    
    public function saleOrder() {
        return $this->hasOne('App\SaleOrder', 'id', 'idSaleOrder');
    }
}
