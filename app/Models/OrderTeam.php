<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTeam extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'orderteam';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];
}
