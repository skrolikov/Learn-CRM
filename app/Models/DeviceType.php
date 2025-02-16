<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(DeviceModel::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
