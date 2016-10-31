<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Kb extends Eloquent {

    protected $table = 'kb';
    public $timestamps = false;

    public function company() {
        return $this->hasOne('App\Company', 'id', 'idCompany');
    }
    public function contact() {
        return $this->hasOne('App\Project', 'id', 'idProject');
    }
}