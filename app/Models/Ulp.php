<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulp extends Model
{
    public function up3()
    {
        return $this->belongsTo(Up3::class);
    }
}
