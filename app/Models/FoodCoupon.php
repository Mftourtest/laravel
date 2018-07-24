<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCoupon extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'food_coupon';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];
}
