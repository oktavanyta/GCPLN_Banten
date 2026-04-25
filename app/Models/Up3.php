<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Up3 extends Model
{
    public function ulp()
    {
        return $this->hasMany(Ulp::class);
    }

    public function upi()
    {
        return $this->belongsTo(Upi::class);
    }
}
