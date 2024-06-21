<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class InvoiceItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "invoice_id","order_id", "item_id", "type_of_sale", "total_meter","total_rolls", "price"
    ];

    protected $dates = ['deleted_at'];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice')->withTrashed();
    }
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
    public function invoice_item_roll()
    {
        return $this->hasMany('App\InvoiceItemRoll')->withTrashed();
    }
}

