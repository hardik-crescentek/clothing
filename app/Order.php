<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "customer_id", "seller_id",'order_date', "note", "status", "name","mobile","address", "booking_days", "remark","role_cutter_name","payment_term","credit_day","entered_by","arranged_by","inspected_by","delivered_by","delivered_date","total_number_of_items","approximate_weight","vat_percentage","vat_amount",'grand_total'
    ];
    // protected $casts = [
    //     'order_date' => 'datetime',
    //     'delivered_date' => 'datetime'
    // ];
    
    protected $dates = ['deleted_at'];

    // public function setOrderDateAttribute($value)
    // {
    //     $this->attributes['order_date'] = empty($value) ? date('Y-m-d H:i') : Carbon::parse($value)->format('Y-m-d H:i');
    //     // $this->attributes['order_date'] = empty($value) ? date('Y-m-d H:i') : date("Y-m-d H:i:s",strtotime($value));
    // }

    // public function setOrderDateAttribute($value)
    // {
    //     if (empty($value)) {
    //         $this->attributes['order_date'] = date('Y-m-d H:i');
    //     } else {
    //         // Parse the date using the correct format
    //         $this->attributes['order_date'] = Carbon::createFromFormat('d/m/Y H:i', $value)->format('Y-m-d H:i');
    //     }
    // }

    // public function setDeliveredDateAttribute($value)
    // {
    //     if (empty($value)) {
    //         $this->attributes['delivered_date'] = date('Y-m-d H:i');
    //     } else {
    //         // Parse the date using the correct format
    //         $this->attributes['delivered_date'] = Carbon::createFromFormat('d/m/Y H:i', $value)->format('Y-m-d H:i');
    //     }
    // }

    // public function getOrderDateAttribute($value)
    // {
    //     return Carbon::parse($value)->format('d/m/Y H:i');
    // }
    public function customer()
    {
        return $this->belongsTo('App\User')->withTrashed()->withDefault();
    }
    public function seller()
    {
        return $this->belongsTo('App\User')->withTrashed()->withDefault();
    }
    public function invoice()
    {
        return $this->hasOne('App\Invoice')->withTrashed();
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }
    public function order_items()
    {
        return $this->hasMany('App\OrderItem', 'order_id')->withTrashed();
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase','item_id')->withTrashed();
    }

    public function purchase_items()
    {
        return $this->hasMany('App\PurchaseItem', 'purchase_id');
    }
}
