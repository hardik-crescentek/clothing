<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Purchase extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    
    protected $fillable = [
        "invoice_no", "purchase_date","user_id","supplier_id", "total_qty","total_tax","shipping_cost_per_meter",'discount', "price", "thb_ex_rate", "price_thb", "payment_terms" ,"purchase_type" ,"currency_of_purchase" ,"shipping_paid" ,"transportation", "gross_tax", "shippment_cost_shipper" ,"shippment_cost_destination", "attachment", "note", "status" ,"total_meter","ex_rate","total_yard","import_tax","transport_shipping_paid","transport_shippment_cost_per_meter","no_of_rolls","no_of_bales"
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'purchase_date' => 'datetime',        
    ];
    
    protected $dates = ['deleted_at'];

    public function setPurchaseDateAttribute($value)
    {
        $this->attributes['purchase_date'] = empty($value) ? date('Y-m-d') : Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getPurchaseDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier')->withTrashed();
    }

    public function purchase_items()
    {
        return $this->hasMany('App\PurchaseItem', 'purchase_id');
    }
    

}
