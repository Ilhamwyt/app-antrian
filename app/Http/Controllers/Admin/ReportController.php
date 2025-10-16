<?php

namespace App\Http\Controllers\Admin;

use App\Exports\VisitorsExport;
use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Visitor::with('queue')->latest();
        
        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }
        
        $visitors = $query->paginate(10);
        
        return view('admin.laporan', compact('visitors'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'complaint' => 'nullable|string',
        ]);
        
        Visitor::create($request->all());
        
        return redirect()->route('admin.laporan.index')
            ->with('success', 'Data pengunjung berhasil ditambahkan');
    }
    
    public function update(Request $request, Visitor $visitor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'complaint' => 'nullable|string',
        ]);
        
        $visitor->update($request->all());
        
        return redirect()->route('admin.laporan.index')
            ->with('success', 'Data pengunjung berhasil diperbarui');
    }
    
    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        
        return redirect()->route('admin.laporan.index')
            ->with('success', 'Data pengunjung berhasil dihapus');
    }
    
    public function export(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        return Excel::download(new VisitorsExport($startDate, $endDate), 'visitors.xlsx');
    }
}
