<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SalesPersonCommision extends Model
{
    use SoftDeletes;

     protected $fillable = [
        "sales_person_id","order_id","commision_type","unit_commision","subtotal_commision","subtotal_commision_type","subtotal_commision_amount"
    ];

    protected $dates = ['deleted_at'];

}
