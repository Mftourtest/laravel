<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodUserCoupon extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'food_user_coupon';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function coupon()
    {
        return $this->belongsTo('App\Models\FoodCoupon', 'coupon_id', 'id');
    }
}
