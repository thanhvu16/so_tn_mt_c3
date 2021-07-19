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

class thongKeVanBanDenGiaiQuyetExport implements FromView, ShouldAutoSize, WithEvents
{

    protected $vanbanden;
    protected $totalReCord;
    protected $thang;
    protected $ngay;
    protected $nam;

    public function __construct($ds_vanBanDen,$totalReCord,$month,$year,$day)
    {
        $this->vanbanden = $ds_vanBanDen;
        $this->thang = $month;
        $this->nam = $year;
        $this->ngay = $day;
        $this->totalReCord = $totalReCord + 3;
        foreach ($ds_vanBanDen as $data)
        {
            $trichYeu= explode(' ', $data->trich_yeu);
            $string = '';
            for ($i=0 ; $i <count($trichYeu) ; $i++)
            {
                $string .= $trichYeu[$i].' ';

                if($i%7 == 0 && $i>=7)
                {
                    $string .= '<br>';
                }

            }



            $data->trich_yeu = $string;

        }
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        return view('vanbanden::thong_ke.TK_vb_den_don_vi_giai_quyet',[
            'ds_vanBanDen' => $this->vanbanden,
            'year' => $this->nam,
            'day' =>  $this->ngay,
            'month' => $this->thang,
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

                $event->sheet->getDelegate()->getStyle('A3:H3')
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

