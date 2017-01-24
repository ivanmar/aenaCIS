<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProductInOut extends Eloquent
{
    protected $table = 'productInOut';
    public $timestamps = false;
    
    public function product() {
        return $this->hasOne('App\Product', 'id', 'idProduct');
    }
}
