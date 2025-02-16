<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubStatus extends Model
{
    protected $fillable = ['name', 'type_status_id'];

    public function typeStatus()
    {
        return $this->belongsTo(TypeStatus::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
