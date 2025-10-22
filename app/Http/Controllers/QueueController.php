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
            
            // Broadcast event untuk update realtime
            \Log::info('Broadcasting queue updated event', [
                'queue_id' => $queue->id,
                'queue_number' => $queue->queue_number,
                'counter_id' => $queue->counter_id
            ]);
            event(new \App\Events\QueueUpdated($queue));
            
            // Redirect ke halaman hasil dengan data antrian
            return response()->json([
                'success' => true,
                'queue_id' => $queue->id,
                'queue_number' => $queueNumber,
                'counter_name' => $counter->nama_loket,
                'counter_id' => $counter->id,
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
        // ... logika untuk mencari antrian berikutnya ...
        // Misalnya:
        $nextQueue = Queue::where('counter_id', $request->counter_id)
                          ->where('status', 'waiting')
                          ->orderBy('created_at', 'asc')
                          ->first();

        if ($nextQueue) {
            $nextQueue->status = 'called';
            $nextQueue->save();

            // *** TAMBAHKAN KODE INI UNTUK MENGIRIM EVENT KE MONITOR ***
            // Ambil nama loket untuk pengumuman
            $counter = Counter::find($request->counter_id);
            
            // Broadcast event ke channel 'monitor-channel'
            // Pastikan Anda memiliki event class yang sesuai, atau gunakan cara sederhana:
            \Log::info('Broadcasting queue called event', [
                'queue_id' => $nextQueue->id,
                'queue_number' => $nextQueue->queue_number,
                'counter_id' => $counter->id,
                'counter_name' => $counter->nama_loket
            ]);
            broadcast(new \App\Events\QueueCalledEvent($nextQueue, $counter))->toOthers();

            return response()->json([
                'success' => true,
                'queue' => $nextQueue,
                'message' => 'Antrian berhasil dipanggil.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada antrian dalam daftar tunggu.'
        ]);
    }

    public function recall(Request $request)
    {
        $queue = Queue::find($request->queue_id);

        if ($queue && $queue->status === 'called') {
            // Broadcast event "recall" ke monitor
            $counter = Counter::find($queue->counter_id);
            \Log::info('Broadcasting queue recalled event', [
                'queue_id' => $queue->id,
                'queue_number' => $queue->queue_number,
                'counter_id' => $counter->id,
                'counter_name' => $counter->nama_loket
            ]);
            broadcast(new \App\Events\QueueRecalledEvent($queue, $counter))->toOthers();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan atau status tidak valid.']);
    }

    // API endpoint untuk mendapatkan antrian yang sedang dipanggil
    public function getCurrentCalledQueue()
    {
        $calledQueue = Queue::where('status', 'called')
            ->whereDate('created_at', now()->toDateString())
            ->with('counter')
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($calledQueue) {
            return response()->json([
                'success' => true,
                'has_queue' => true,
                'queue' => [
                    'id' => $calledQueue->id,
                    'queue_number' => $calledQueue->queue_number,
                    'status' => $calledQueue->status,
                    'updated_at' => $calledQueue->updated_at->format('Y-m-d H:i:s')
                ],
                'counter' => [
                    'id' => $calledQueue->counter->id,
                    'nama_loket' => $calledQueue->counter->nama_loket
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'has_queue' => false,
            'message' => 'Tidak ada antrian yang sedang dipanggil'
        ]);
    }

    // API endpoint untuk mendapatkan daftar antrian berdasarkan counter
    public function getQueuesByCounter($counterId)
    {
        $queues = Queue::where('counter_id', $counterId)
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'asc')
            ->get();

        $currentQueue = $queues->where('status', 'called')->first();

        return response()->json([
            'success' => true,
            'queues' => $queues,
            'current_queue' => $currentQueue,
            'waiting_count' => $queues->where('status', 'waiting')->count(),
            'called_count' => $queues->where('status', 'called')->count(),
            'served_count' => $queues->where('status', 'served')->count()
        ]);
    }
}