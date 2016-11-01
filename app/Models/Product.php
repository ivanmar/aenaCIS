<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Product extends Eloquent
{
    protected $table = 'product';
    public $timestamps = false;
    
    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
    public function productgroup() {
        return $this->hasOne('App\ProductGroup', 'id', 'idProductGroup');
    }
}
