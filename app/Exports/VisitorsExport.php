<?php

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitorsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Visitor::with(['queue']);
        
        if ($this->startDate && $this->endDate) {
            $query->whereDate('created_at', '>=', $this->startDate)
                  ->whereDate('created_at', '<=', $this->endDate);
        }
        
        return $query->get();
    }
    
    /**
     * @var Visitor $visitor
     */
    public function map($visitor): array
    {
        return [
            $visitor->id,
            $visitor->queue->queue_number,
            $visitor->name,
            $visitor->phone,
            $visitor->complaint,
            $visitor->created_at->format('d/m/Y H:i:s'),
        ];
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Nomor Antrian',
            'Nama',
            'No. HP',
            'Keluhan',
            'Tanggal',
        ];
    }
}
