<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upi extends Model
{
    public function up3()
    {
        return $this->hasMany(Up3::class);
    }
}
