<?php

namespace App\Exports;

use App\Models\VanBanDi;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class thongKeVanBanChiCucExport implements FromView, ShouldAutoSize, WithEvents
{

    protected $danhSachDonVi;

    protected $totalReCord;
    protected $tongSoVB;
    protected $donViChiCuc;
    protected $tu_ngay;
    protected $den_ngay;

    public function __construct($danhSachDonVi, $totalReCord,$tongSoVB,$donViChiCuc,$tu_ngay,$den_ngay)
    {

        $this->danhSachDonVi = $danhSachDonVi;
        $this->totalReCord=$totalReCord + 6;
        $this->tongSoVB=$tongSoVB;
        $this->donViChiCuc=$donViChiCuc;
        $this->tu_ngay=$tu_ngay;
        $this->den_ngay=$den_ngay;
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        return view('vanbanden::thong_ke.TK_vb_chi_cuc',[
            'danhSachDonVi' => $this->danhSachDonVi,
            'tongSoVB' => $this->tongSoVB,
            'donViChiCuc' => $this->donViChiCuc,
            'tu_ngay' => $this->tu_ngay,
            'den_ngay' => $this->den_ngay,
        ]);
    }


    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $cellRange = 'A1:G1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'name'      =>  'Times New Roman',
                        'bold' => true,
                        'size' => 13,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A4:G5')
                    ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()
                    ->setARGB('caeaef');

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];


                $event->sheet->getStyle('A2:' . $event->sheet->getDelegate()->getHighestDataColumn() . $this->totalReCord)->applyFromArray($styleArray);
            }
        ];
    }

}

