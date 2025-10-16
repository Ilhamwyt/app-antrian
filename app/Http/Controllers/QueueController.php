<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QueueController extends Controller
{
    // Mengambil nomor antrian baru
    public function take(Request $request)
    {
        $request->validate([
            'counter_id' => 'required|exists:counters,id',
        ]);

        $counterId = $request->counter_id;
        
        // Mendapatkan counter
        $counter = \App\Models\Counter::findOrFail($counterId);
        
        // Mendapatkan nomor antrian terakhir untuk counter ini
        $lastQueue = \App\Models\Queue::where('counter_id', $counterId)
            ->whereDate('created_at', today())
            ->latest()
            ->first();
        
        // Menentukan nomor antrian baru
        $queueNumber = 1;
        if ($lastQueue) {
            // Ekstrak nomor dari format (misal: A001)
            $lastNumber = intval(substr($lastQueue->queue_number, 1));
            $queueNumber = $lastNumber + 1;
        }
        
        // Format nomor antrian (misal: A001)
        $prefix = substr($counter->nama_loket, 0, 1);
        $formattedNumber = $prefix . str_pad($queueNumber, 3, '0', STR_PAD_LEFT);
        
        // Membuat antrian baru
        $queue = \App\Models\Queue::create([
            'counter_id' => $counterId,
            'queue_number' => $formattedNumber,
            'status' => 'waiting',
        ]);
        
        return response()->json([
            'success' => true,
            'queue' => $queue,
            'counter' => $counter->nama_loket,
        ]);
    }
    
    // Mendapatkan daftar antrian untuk loket tertentu
    public function getByCounter($counterId)
    {
        $queues = \App\Models\Queue::where('counter_id', $counterId)
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json($queues);
    }
    
    // Memperbarui status antrian
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:waiting,called,absent,served',
        ]);
        
        $queue = \App\Models\Queue::findOrFail($id);
        
        // Update status dan timestamp yang sesuai
        $queue->status = $request->status;
        
        if ($request->status == 'called') {
            $queue->called_at = now();
        } elseif ($request->status == 'served') {
            $queue->served_at = now();
        }
        
        $queue->save();
        
        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }
}
