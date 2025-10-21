<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Counter;
use App\Models\Visitor;
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
    
    // Fungsi untuk menandai antrian sebagai tidak hadir (absent)
    public function markAbsent(Request $request)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id'
        ]);
        
        try {
            $queue = Queue::find($request->queue_id);
            
            // Pastikan antrian ini sedang dipanggil (status = called)
            if ($queue->status !== 'called') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya antrian yang sedang dipanggil yang dapat ditandai tidak hadir'
                ], 400);
            }
            
            // Update status menjadi absent
            $queue->update([
                'status' => 'absent',
                'completed_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Antrian berhasil ditandai tidak hadir'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai antrian tidak hadir: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Fungsi untuk melayani antrian
    public function serveQueue(Request $request)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'nim' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'complaint' => 'nullable|string',
            'solution' => 'nullable|string',
            'status' => 'required|string',
            'forward_to' => 'nullable|string'
        ]);
        
        try {
            $queue = Queue::find($request->queue_id);
            
            // Pastikan antrian ini sedang dipanggil (status = called)
            if ($queue->status !== 'called') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya antrian yang sedang dipanggil yang dapat dilayani'
                ], 400);
            }
            
            // Simpan data pengunjung
            $visitor = new Visitor();
            $visitor->queue_id = $queue->id;
            $visitor->nim = $request->nim;
            $visitor->name = $request->name;
            $visitor->phone = $request->phone;
            $visitor->complaint = $request->complaint;
            $visitor->solution = $request->solution;
            $visitor->status = $request->status;
            $visitor->forward_to = $request->forward_to;
            $visitor->save();
            
            // Update status menjadi served
            $queue->update([
                'status' => 'served',
                'completed_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pengunjung berhasil dilayani'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melayani pengunjung: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Fungsi untuk memanggil antrian berikutnya
    public function callNext(Request $request)
    {
        $request->validate([
            'counter_id' => 'required|exists:counters,id'
        ]);
        
        $counterId = $request->counter_id;
        
        // Cari antrian berikutnya dengan status waiting
        $nextQueue = Queue::where('counter_id', $counterId)
                        ->where('status', 'waiting')
                        ->whereDate('created_at', Carbon::today())
                        ->orderBy('created_at', 'asc')
                        ->first();
        
        if (!$nextQueue) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrian yang menunggu'
            ]);
        }
        
        // Update status antrian menjadi called
        $nextQueue->status = 'called';
        $nextQueue->called_at = Carbon::now();
        $nextQueue->save();
        
        return response()->json([
            'success' => true,
            'queue' => $nextQueue
        ]);
    }
}