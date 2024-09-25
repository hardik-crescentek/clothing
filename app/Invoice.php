<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "invoice_no","order_id","customer_id", "seller_id","payment_receiver_id","sub_total","payment_terms","credit_days","sales_type","charge_in_unit","sales_commision","commision_type","commision_amount_thb","commision_amount_thb","commision_amount_sale","tax","discount","discount_type","grand_total",'invoice_date', "note", "status" ,"vat_percentage","vat_amount"
    ];
    protected $casts = [
        'order_date' => 'datetime',        
    ];
    
    protected $dates = ['deleted_at'];

    // public function setInvoiceDateAttribute($value)
    // {
    //     $this->attributes['invoice_date'] = empty($value) ? date('Y-m-d') : Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    // }
    public function setInvoiceDateAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['order_date'] = Carbon::createFromFormat('d/m/Y H:i', $value)->format('Y-m-d H:i:s');
        }
    }
    public function getInvoiceDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function customer()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    public function seller()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    public function paymentReceiver()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }
    public function invoice_items()
    {
        return $this->hasMany('App\InvoiceItem', 'invoice_id')->withTrashed();
    }
    public function invoice_item_rolls()
    {
        return $this->hasMany('App\InvoiceItemRoll', 'invoice_id')->withTrashed();
    }
    public function order()
    {
        return $this->hasMany('App\Order', 'order_id')->withTrashed();
    }
    public function payment_history()
    {
        return $this->hasMany('App\PaymentHistory', 'invoice_id')->withTrashed();
    }
}
