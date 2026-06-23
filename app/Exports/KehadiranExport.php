<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KehadiranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Ambil data koleksi kehadiran
     */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * Header Kolom Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nomor Perkara',
            'Agenda Sidang',
            'Tanggal Sidang',
            'Jam Sidang',
            'Nama Pihak',
            'Status Pihak',
            'Nomor HP',
            'Waktu Hadir',
            'Status Kehadiran',
        ];
    }

    /**
     * Mapping data baris demi baris
     */
    private $rowNum = 0;
    public function map($kehadiran): array
    {
        $this->rowNum++;
        $pihak = $kehadiran->pihakSidang;
        $jadwal = $pihak->jadwalSidang;
        $perkara = $jadwal->perkara;

        $tanggal = $jadwal->tanggal_sidang instanceof \Carbon\Carbon 
            ? $jadwal->tanggal_sidang->format('d-m-Y') 
            : \Carbon\Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y');

        return [
            $this->rowNum,
            $perkara->nomor_perkara,
            $jadwal->agenda_sidang,
            $tanggal,
            substr($jadwal->jam_sidang, 0, 5),
            $pihak->nama,
            $pihak->status_pihak,
            $pihak->nomor_hp,
            $kehadiran->waktu_hadir ? $kehadiran->waktu_hadir->format('d-m-Y H:i:s') : '-',
            $kehadiran->status_hadir,
        ];
    }

    /**
     * Styling baris header
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0B2A49'] // Slate Blue Premium
                ]
            ],
        ];
    }
}
