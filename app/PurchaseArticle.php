<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseArticle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_article_id','purchase_id','material_id','article'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'purchase_article_color');
    }
}
