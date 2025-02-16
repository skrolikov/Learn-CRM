<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceBrand extends Model
{
    protected $fillable = ['name', 'device_model_id'];

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModel::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
