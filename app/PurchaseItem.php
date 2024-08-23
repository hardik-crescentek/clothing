<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PurchaseItem extends Model
{
    use SoftDeletes;

    protected $table = 'purchase_items';

    protected $fillable = [
        "purchase_id","material_id","roll_no","color","color_no","article_no","batch_no","barcode","qrcode","width","qty","available_qty","status", "return_status", 'sort_order','total_qty',"piece_no","cost_per_mtr","cost_per_yrd"
    ];
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function purchase()
    {
        return $this->belongsTo('App\Purchase','purchase_id')->withTrashed();
    }
    public function material()
    {
        return $this->belongsTo('App\Material')->withTrashed();
    }
    public function color()
    {
        return $this->belongsTo('App\Color')->withTrashed();
    }

}
