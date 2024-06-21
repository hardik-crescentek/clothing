<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class InvoiceItemRoll extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "invoice_item_id","invoice_id", "roll_id", "roll_no","return_status","meter"
    ];

    protected $dates = ['deleted_at'];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice')->withTrashed();
    }
    public function invoice_item()
    {
        return $this->belongsTo('App\InvoiceItem')->withTrashed();
    }
    public function roll()
    {
        return $this->belongsTo('App\PurchaseItem','roll_id')->withTrashed();
    }   
}
