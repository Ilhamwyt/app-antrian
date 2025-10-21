<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Queue;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $counters = Counter::all();
        return view('admin.manajemenLoket', compact('counters'));
    }
    
    /**
     * Display the home page with counters
     */
    public function home()
    {
        $counters = Counter::all();
        return view('home', compact('counters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_loket' => 'required|string|max:255',
            'layanan_id' => 'nullable|exists:layanan,id',
        ]);

        Counter::create([
            'nama_loket' => $request->nama_loket,
            'layanan_id' => $request->layanan_id,
        ]);

        return redirect()->route('manajemenLoket')->with('success', 'Loket berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $counter = Counter::findOrFail($id);
        return response()->json($counter);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_loket' => 'required|string|max:255',
            'layanan_id' => 'nullable|exists:layanan,id',
        ]);

        $counter = Counter::findOrFail($id);
        $counter->update([
            'nama_loket' => $request->nama_loket,
            'layanan_id' => $request->layanan_id,
        ]);

        return redirect()->route('manajemenLoket')->with('success', 'Loket berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $counter = Counter::findOrFail($id);
        $counter->delete();

        return redirect()->route('manajemenLoket')->with('success', 'Loket berhasil dihapus');
    }
    
    /**
     * Get all counters for API
     */
    public function getCounters()
    {
        $counters = Counter::all();
        return response()->json($counters);
    }
    
    /**
     * Display the counter page for queue management
     */
    public function show(string $id, Request $request)
    {
        $counter = Counter::findOrFail($id);
        $queueNumber = $request->query('queue_number');
        
        //Ambil Daftar Antrian Untuk COunter Ini
        $queues = Queue::where('counter_id', $id)
            ->whereDate('created_at', now())
            ->orderBy('id', 'asc')
            ->get();
        
        return view('loket.loket', compact('counter', 'queues', 'queueNumber'));
    }
}
