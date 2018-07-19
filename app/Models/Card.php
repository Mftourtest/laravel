<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'card';
    protected $primaryKey   = 'id';
    protected $keyType      = 'varchar';
    public $incrementing    = false;
    public $timestamps      = false;

    protected $guarded = [];
}
