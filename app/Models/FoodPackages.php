<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodPackages extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'food_packages';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];
}
