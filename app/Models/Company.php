<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Company extends Eloquent
{
    protected $table = 'company';
    public $timestamps = false;
}
