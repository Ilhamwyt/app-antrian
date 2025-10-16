<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Queue extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'counter_id',
        'queue_number',
        'status',
        'called_at',
        'completed_at'
    ];
    
    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
    
    // Method untuk generate nomor antrian baru
    public static function generateNumber($counterId)
    {
        // Cari counter untuk memastikan valid
        $counter = Counter::find($counterId);
        
        if (!$counter) {
            throw new \Exception('Counter not found');
        }
        
        // Ambil nomor antrian terakhir untuk hari ini (semua counter)
        $lastQueue = self::whereDate('created_at', Carbon::today())
            ->orderBy('queue_number', 'desc')
            ->first();
        
        // Generate nomor antrian baru
        if ($lastQueue) {
            // Ekstrak angka dari nomor antrian terakhir (misal 001 -> 1)
            $lastNumber = intval($lastQueue->queue_number);
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada antrian hari ini, mulai dari 1
            $newNumber = 1;
        }
        
        // Format nomor antrian (001, 002, dst)
        return str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}