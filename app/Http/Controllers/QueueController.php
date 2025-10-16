<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QueueController extends Controller
{
    // Fungsi untuk mengambil nomor antrian
    public function take(Request $request)
    {
        $request->validate([
            'counter_id' => 'required|exists:counters,id'
        ]);
        
        $counter = Counter::find($request->counter_id);
        
        try {
            // Generate nomor antrian (format 001, 002, dst)
            $queueNumber = Queue::generateNumber($request->counter_id);
            
            // Simpan ke database
            $queue = Queue::create([
                'counter_id' => $request->counter_id,
                'queue_number' => $queueNumber,
                'status' => 'waiting'
            ]);
            
            // Hitung posisi antrian
            $queuePosition = Queue::where('status', 'waiting')
                ->whereDate('created_at', Carbon::today())
                ->where('id', '<=', $queue->id)
                ->count();
            
            // Estimasi waktu tunggu (asumsi 5 menit per antrian)
            $estimatedWaitTime = ($queuePosition - 1) * 5;
            
            // Redirect ke halaman hasil dengan data antrian
            return response()->json([
                'success' => true,
                'queue_id' => $queue->id,
                'queue_number' => $queueNumber,
                'counter_name' => $counter->nama_loket,
                'queue_position' => $queuePosition,
                'estimated_wait_time' => $estimatedWaitTime
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghasilkan nomor antrian: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Fungsi untuk menampilkan hasil antrian
    public function result(Request $request)
    {
        $queueId = $request->query('queueId');
        
        if (!$queueId) {
            return redirect('/')->with('error', 'Nomor antrian tidak ditemukan');
        }
        
        $queue = Queue::find($queueId);
        
        if (!$queue) {
            return redirect('/')->with('error', 'Nomor antrian tidak ditemukan');
        }
        
        return view('queues.result', [
            'queueId' => $queue->id,
            'queueNumber' => $queue->queue_number,
            'counterName' => $queue->counter->nama_loket,
            'queuePosition' => $request->query('queue_position', 1),
            'estimatedWaitTime' => $request->query('estimated_wait_time', 0)
        ]);
    }
    
    // Fungsi untuk mengambil data antrian berdasarkan loket
    public function getByCounter($counterId)
    {
        // Validasi counter ID
        $counter = Counter::find($counterId);
        if (!$counter) {
            return redirect('/')->with('error', 'Loket tidak ditemukan');
        }
        
        // Ambil semua antrian untuk loket ini pada hari ini
        $queues = Queue::where('counter_id', $counterId)
                      ->whereDate('created_at', Carbon::today())
                      ->orderBy('created_at', 'asc')
                      ->get();
        
        return view('loket.loket', [
            'counter' => $counter,
            'queues' => $queues
        ]);
    }
}