<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groundcheck extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'jenis', 
        'ulp_id', 
        'open', 
        'submitted', 
        'rejected', 
        'created_at'
    ];

    protected $casts = [
        'open' => 'integer',
        'submitted' => 'integer',
        'rejected' => 'integer',
        'created_at' => 'datetime',
    ];

    public function ulp()
    {
        return $this->belongsTo(Ulp::class);
    }
}
