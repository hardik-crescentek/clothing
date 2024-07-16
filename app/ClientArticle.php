<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class ClientArticle extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'article_no',
        'color_no',
        'roll',
        'cut_wholesale',
        'retail',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

}
