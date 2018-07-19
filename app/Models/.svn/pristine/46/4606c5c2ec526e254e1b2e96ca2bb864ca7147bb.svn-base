<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'food_cate';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function foods()
    {
        return $this->hasMany('App\Models\Foods', 'cate_id', 'id');
    }
}
