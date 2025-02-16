<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeStatus extends Model
{
    protected $fillable = ['name'];

    public function substatuses()
    {
        return $this->hasMany(SubStatus::class);
    }
}
