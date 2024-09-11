<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;

class Material extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "name", "category_id","selvage","construction", "color","color_no","article_no",'width','weight', "image", "barcode", "description", "min_alert_qty", "status","wholesale_price","retail_price","sample_price","supplier_id","made_in","currency","price","roll","roll_per_mtr","cut_wholesale","cut_wholesale_per_mtr","retail","retail_per_mtr","width_inch","width_cm","weight_gsm","weight_per_mtr","weight_per_yard","unit_purchased_in"
    ];

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo('App\Category')->withTrashed();
    }

    public function color()
    {
        return $this->belongsTo('App\Color')->withTrashed();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }
    public function getFullNameAttribute()
    {
        return $this->attributes['name'] . " - " . $this->attributes['color'];
    }
    public function getImageUrlAttribute()
    {
        if($this->image){
            return asset('/uploads/'.$this->image);
        }
        return asset('/images/no_image.png');
    }

    public function purchaseItems()
    {
        return $this->hasMany('App\PurchaseItem', 'material_id', 'id')->withTrashed();
    }

    public function getColorCodeAttribute()
    {
        $return = $this->color_no;
        if($this->color){
        $return .=  "({$this->color})";
        }
        return $return;

    }



    protected $appends = ['image_url','color_code'];

}
