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
        'roll_per_mtr',
        'cut_wholesale',
        'cut_wholesale_per_mtr',
        'retail',
        'retail_per_mtr',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

}
