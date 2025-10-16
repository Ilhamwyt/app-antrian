<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'queue_id',
        'name',
        'phone',
        'complaint'
    ];
    
    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
