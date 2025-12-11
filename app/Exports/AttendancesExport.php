<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'Role',
            'Kantor',
            'Tanggal',
            'Hari',
            'Check-in',
            'Check-out',
            'Status',
            'Catatan',
        ];
    }

    public function map($attendance): array
    {
        static $no = 1;
        
        return [
            $no++,
            $attendance->user->name ?? '-',
            $attendance->user->email ?? '-',
            $attendance->user->role_display ?? '-',
            $attendance->user->office->name ?? '-',
            $attendance->date->format('d/m/Y'),
            $attendance->date->format('l'),
            $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : '-',
            $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : '-',
            $this->getStatusText($attendance->status),
            $attendance->notes ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 30,
            'D' => 15,
            'E' => 20,
            'F' => 12,
            'G' => 12,
            'H' => 12,
            'I' => 12,
            'J' => 15,
            'K' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Center alignment untuk kolom tertentu
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    private function getStatusText($status)
    {
        return match($status) {
            'present' => 'Tepat Waktu',
            'late' => 'Terlambat',
            'early_out' => 'Pulang Mendahului',
            default => 'Tidak Hadir',
        };
    }
}

