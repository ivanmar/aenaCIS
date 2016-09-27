<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Customer extends Eloquent
{
    protected $table = 'customer';
    public $timestamps = false;
}
