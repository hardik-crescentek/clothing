<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WareHouse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "name","address"
    ];
}
