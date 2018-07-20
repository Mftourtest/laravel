<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'team';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function partner()
    {
        return $this->belongsTo('App\Models\Partner', 'partner_id', 'id');
    }
}
