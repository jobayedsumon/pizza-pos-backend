<?php

namespace App\Model;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $casts = [
        'tax' => 'float',
        'price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'set_menu' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'product_type' => 'string'
    ];

    public function getPriceAttribute($price)
    {
        return (float)Helpers::set_price($price);
    }

    public function getDiscountAttribute($discount)
    {
        return (float)Helpers::set_price($discount);
    }

    public function translations()
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', '=', 1);
    }

    public function scopeProductType($query, $type)
    {
        if($type == 'veg') {
            return $query->where('product_type', 'veg');
        } elseif($type == 'meat') {
            return $query->where('product_type', 'meat');
        } elseif($type == 'chicken') {
            return $query->where('product_type', 'chicken');
        } elseif($type == 'sea_food') {
            return $query->where('product_type', 'sea_food');
        }
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function rating()
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, product_id'))
            ->groupBy('product_id');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class)->latest();
    }

    protected static function booted()
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function($query){
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
}
