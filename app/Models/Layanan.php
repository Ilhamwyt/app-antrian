<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $fillable = ['nama_layanan'];
    
    public function counters()
    {
        return $this->hasMany(Counter::class);
    }
}
