<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'counter_id',
        'queue_number',
        'status',
        'called_at',
        'served_at'
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'served_at' => 'datetime',
    ];

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}
