<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'first_name', 'middle_name', 'phone', 'device'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
