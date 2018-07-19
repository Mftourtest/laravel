<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodArea extends Model
{
    //指定表名
    protected $table = 'food_area';

    //指定主键
    protected $primaryKey = 'id';

    public function desk()
    {
        return $this->hasMany('App\Model\Food_area_desk','area_id','id');
    }


}


