<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'sku', 'price', 'service_category_id'];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_service');
    }
}
