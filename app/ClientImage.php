<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id', 'name'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
