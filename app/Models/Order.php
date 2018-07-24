<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'order';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function orderTeam()
    {
        return $this->hasMany('App\Models\OrderTeam', 'orderid', 'id');
    }
}
