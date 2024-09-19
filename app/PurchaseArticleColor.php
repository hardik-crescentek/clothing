<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseArticleColor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_article_id','purchase_id','material_id','color','color_no'
    ];

    public function purchaseArticles()
    {
        return $this->belongsToMany(PurchaseArticle::class, 'purchase_article_color');
    }
}
