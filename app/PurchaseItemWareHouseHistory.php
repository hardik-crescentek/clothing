<?php

namespace App;

use App\WareHouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PurchaseItemWareHouseHistory extends Model
{
    use SoftDeletes;

    protected $table = 'purchase_items_warehouse_history';

    protected $fillable = [
        'purchase_item_id',
        'old_warehouse_id',
        'current_warehouse_id',
        'changed_at',
        'moved_by',
        'transported_by'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'current_warehouse_id');
    }

}
