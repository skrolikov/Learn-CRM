<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['device_id', 'client_id', 'sub_status_id'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function subStatus()
    {
        return $this->belongsTo(SubStatus::class, 'sub_status_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_service');
    }
}
