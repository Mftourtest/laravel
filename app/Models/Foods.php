<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'food';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function packages()
    {
        return $this->hasMany('App\Models\FoodPackages', 'food_id', 'id');
    }
}
