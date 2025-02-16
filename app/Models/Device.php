<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_type_id', 'device_model_id', 'device_brand_id', 'device_color_id', 'imei'];

    public function type()
    {
        return $this->belongsTo(DeviceType::class, 'device_type_id');
    }

    public function model()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id');
    }

    public function brand()
    {
        return $this->belongsTo(DeviceBrand::class, 'device_brand_id');
    }

    public function color()
    {
        return $this->belongsTo(DeviceColor::class, 'device_color_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
