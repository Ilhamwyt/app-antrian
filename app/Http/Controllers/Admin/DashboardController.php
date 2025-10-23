<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Layanan;
use App\Models\Queue;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getData(Request $request)
    {
        $period = $request->input('period', 'today');
        
        // Menentukan rentang tanggal berdasarkan filter
        $startDate = null;
        $endDate = Carbon::now();
        
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            default:
                $startDate = Carbon::today();
        }
        
        // Mendapatkan data untuk total antrian
        $totalAntrian = Queue::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Mendapatkan data untuk antrian tidak hadir
        $antrianAbsent = Queue::where('status', 'absent')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Mendapatkan data untuk total loket (tidak terfilter)
        $totalLoket = Counter::count();
        
        // Mendapatkan data untuk jumlah layanan (tidak terfilter)
        $totalLayanan = Layanan::count();
        
        // Mendapatkan data untuk chart antrian
        $chartLabels = [];
        $totalAntrianData = [];
        $antrianSelesaiData = [];
        $antrianAbsentData = [];
        
        // Menentukan format tanggal dan jumlah hari berdasarkan filter
        $dateFormat = 'Y-m-d';
        $groupByFormat = 'Y-m-d';
        $labelFormat = 'D'; // Default: hari dalam seminggu (Sen, Sel, dst)
        
        if ($period === 'today') {
            $dateFormat = 'Y-m-d H:00';
            $groupByFormat = 'Y-m-d H';
            $labelFormat = 'H:00'; // Format jam
            $days = 24; // 24 jam
            $interval = 'hour';
        } elseif ($period === 'week') {
            $days = 7; // 7 hari
            $interval = 'day';
        } elseif ($period === 'month') {
            $days = Carbon::now()->daysInMonth; // Jumlah hari dalam bulan
            $interval = 'day';
            $labelFormat = 'd'; // Tanggal (1, 2, 3, dst)
        }
        
        // Membuat array tanggal untuk chart
        $dates = [];
        for ($i = 0; $i < $days; $i++) {
            if ($period === 'today') {
                $date = Carbon::today()->addHours($i);
            } else {
                $date = $startDate->copy()->addDays($i);
            }
            $dates[] = $date;
            $chartLabels[] = $date->format($labelFormat);
        }
        
        // Mendapatkan data antrian per hari/jam
        foreach ($dates as $date) {
            $nextDate = $date->copy();
            if ($period === 'today') {
                $nextDate->addHour();
            } else {
                $nextDate->addDay();
            }
            
            // Total antrian
            $totalAntrianData[] = Queue::whereBetween('created_at', [$date, $nextDate])->count();
            
            // Antrian selesai
            $antrianSelesaiData[] = Queue::where('status', 'served')
                ->whereBetween('created_at', [$date, $nextDate])
                ->count();
            
            // Antrian tidak hadir
            $antrianAbsentData[] = Queue::where('status', 'absent')
                ->whereBetween('created_at', [$date, $nextDate])
                ->count();
        }
        
        // Mendapatkan data untuk chart status laporan
        $statusSelesai = Visitor::where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        $statusTindakLanjut = Visitor::where('status', 'perlu_tindak_lanjut')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Menghitung persentase perubahan dari hari/minggu/bulan sebelumnya
        $previousStartDate = $startDate->copy();
        $previousEndDate = $endDate->copy();
        
        if ($period === 'today') {
            $previousStartDate->subDay();
            $previousEndDate->subDay();
        } elseif ($period === 'week') {
            $previousStartDate->subWeek();
            $previousEndDate->subWeek();
        } elseif ($period === 'month') {
            $previousStartDate->subMonth();
            $previousEndDate->subMonth();
        }
        
        $previousTotalAntrian = Queue::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        $previousAntrianAbsent = Queue::where('status', 'absent')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();
        
        // Menghitung persentase perubahan
        $totalAntrianPercentage = $previousTotalAntrian > 0 
            ? round((($totalAntrian - $previousTotalAntrian) / $previousTotalAntrian) * 100) 
            : 0;
        
        $antrianAbsentPercentage = $previousAntrianAbsent > 0 
            ? round((($antrianAbsent - $previousAntrianAbsent) / $previousAntrianAbsent) * 100) 
            : 0;
        
        // Persentase untuk layanan (dummy data karena tidak ada filter waktu)
        $totalLayananPercentage = 5;
        
        return response()->json([
            'total_antrian' => $totalAntrian,
            'antrian_absent' => $antrianAbsent,
            'total_loket' => $totalLoket,
            'total_layanan' => $totalLayanan,
            'total_antrian_percentage' => $totalAntrianPercentage,
            'antrian_absent_percentage' => $antrianAbsentPercentage,
            'total_layanan_percentage' => $totalLayananPercentage,
            'chart_labels' => $chartLabels,
            'total_antrian_data' => $totalAntrianData,
            'antrian_selesai_data' => $antrianSelesaiData,
            'antrian_absent_data' => $antrianAbsentData,
            'status_selesai' => $statusSelesai,
            'status_tindak_lanjut' => $statusTindakLanjut,
        ]);
    }
}