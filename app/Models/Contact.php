<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Contact extends Eloquent
{
    protected $table = 'contact';
    public $timestamps = false;
    
    public function company() {
        return $this->hasOne('App\Company', 'id', 'idComapny');
    }
}
