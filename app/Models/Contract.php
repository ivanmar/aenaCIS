<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Contract extends Eloquent
{
    protected $table = 'contract';
    public $timestamps = false;
    
    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
}
