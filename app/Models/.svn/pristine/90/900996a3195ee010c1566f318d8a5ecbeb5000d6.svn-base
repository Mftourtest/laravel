<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodAreaDesk extends Model
{

    protected $connection   = 'mysql';
    protected $table        = 'food_area_desk';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function belongsToDesk()
    {
        return $this->belongsTo('App\Model\Food_area', 'area_id', 'id');
    }

    public function belongsToState()
    {
        return $this->belongsTo('App\Model\Food_desk_state', 'desk_state', 'id');
    }
}
