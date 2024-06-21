<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PaymentHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "invoice_id", "payment_receiver_id",'payment_type', "amount", "chequee_no","note","payment_date" 
    ];
    
    protected $casts = [
        'payment_date' => 'datetime',        
    ];

    protected $dates = ['deleted_at'];

    public function setPaymentDateAttribute($value)
    {
        $this->attributes['payment_date'] = empty($value) ? date('Y-m-d') : Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getPaymentDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Invoice')->withTrashed();
    }
    public function paymentReceiver()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
}
