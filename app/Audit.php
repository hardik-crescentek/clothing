<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Audit extends Model
{
    protected $table = 'audit';

    public function customer()
    {
        return $this->belongsTo('App\User','user_id')->withTrashed();
    }
}
