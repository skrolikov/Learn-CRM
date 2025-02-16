<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    protected $fillable = ['name', 'device_type_id'];

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function brands()
    {
        return $this->hasMany(DeviceBrand::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
