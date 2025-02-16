<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceColor extends Model
{
    protected $fillable = ['name'];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
