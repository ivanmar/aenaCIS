<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InvCirc extends Eloquent
{
    protected $table = 'invCirc';
    public $timestamps = false;
    
    public function contract() {
        return $this->hasOne('App\Contract', 'id', 'idContract');
    }
}
