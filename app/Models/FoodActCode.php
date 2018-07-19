<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodActCode extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'food_act_code';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];
}
