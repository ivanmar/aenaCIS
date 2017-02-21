<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Product extends Eloquent
{
    protected $table = 'product';
    public $timestamps = false;
    
    public function manufacturer() {
        return $this->hasOne('App\Manufacturer', 'id', 'idManufacturer');
    }
    public function productgroup() {
        return $this->hasOne('App\ProductGroup', 'id', 'idProductGroup');
    }
}
