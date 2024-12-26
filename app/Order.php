<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\WareHouse;
use App\User;
use Carbon\Carbon;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "customer_id", "seller_id",'order_date', "note", "status", "name","mobile","address", "booking_days", "remark","role_cutter_name","payment_term","price_vat","credit_day","entered_by","arranged_by","inspected_by","delivered_by","delivered_date","total_number_of_items","approximate_weight","vat_percentage","vat_amount",'grand_total','total_profit','status_date','image',"dispatcher_id"
    ];

    protected $dates = ['deleted_at', 'order_date', 'delivered_date'];
    
    public function setOrderDateAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['order_date'] = Carbon::createFromFormat('d/m/Y H:i', $value)->format('Y-m-d H:i:s');
        }
    }

    public function setDeliveredDateAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['delivered_date'] = Carbon::createFromFormat('d/m/Y H:i', $value)->format('Y-m-d H:i:s');
        }
    }
    
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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatcher_id'); 
    }

}
