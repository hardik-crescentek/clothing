<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerItemPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "customer_id","material_id","price","retail_credit_days","wholesale_price","wholesale_credit_days","sample_price","sample_credit_days","remark_note"
    ];

    protected $dates = ['deleted_at'];
    
    public function customer()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    public function material()
    {
        return $this->belongsTo('App\Material')->withTrashed();
    }
}
