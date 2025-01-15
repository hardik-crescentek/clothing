<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "order_id", "item_id", "type_of_sale", "meter", "price","roll_id" ,"item_total","status","status_date","image","barcode"
    ];
    
    protected $dates = ['deleted_at'];

    public function color()
    {
        return $this->belongsTo('App\Color')->withTrashed();
    }
    public function item()
    {
        return $this->belongsTo('App\Material')->withTrashed();
    }
    public function order()
    {
        return $this->belongsTo('App\Order')->withTrashed();
    }
    public function purchase()
    {
        return $this->belongsTo('App\Purchase','item_id')->withTrashed();
    }
    public function purchase_items()
    {
        return $this->hasMany('App\PurchaseItem','material_id','item_id')->withTrashed();
    }
    public function invoice()
    {
        return $this->hasOne('App\Invoice','order_id')->whereNull('deleted_at');
    }

}
