<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [

        "name", "parent_id", "status", "slug"
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // Each category may have one parent
    public function parent()
    {
        // return $this->belongsToOne(static::class, 'category_id');
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function getParentsNames() {
        if($this->parent) {
            //return $this->parent->getParentsNames(). " > " . $this->name;
            return $this->parent->name;
        } else {
            return '-';
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public static function forDropdown($parent_id = 0)
    {
        return Category::where('status', true)->where('parent_id', $parent_id)->pluck('name', 'id')->all();
    }
}
