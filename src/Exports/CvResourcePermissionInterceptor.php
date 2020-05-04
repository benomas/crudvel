<?php

namespace Crudvel\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithEvents, ShouldAutoSize};
use Maatwebsite\Excel\Sheet;

class CvResourcePermissionInterceptor implements FromCollection, WithEvents, ShouldAutoSize
{
  public $collection;
  public $bgRanges = [];

  public function __construct($collection=null, $bgRanges){
    $this->collection= $collection;
    $this->bgRanges= $bgRanges;
  }

  public function collection(){
    return $this->collection;
  }
  public function registerEvents(): array
  {

    // set my macro function to styleCells with background cell ranges
    Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
      $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
    });

    $bgRanges = $this->bgRanges;
    return [
      AfterSheet::class => function (AfterSheet $event) use($bgRanges)
      {
        //IMPORTANT: Protect all the cells in the sheet if I have defined bgRanges
        if(count($bgRanges) > 0)
          $event->sheet->getProtection()->setSheet(true);
        //Iter over all my ranges to set style with background
        foreach ($bgRanges as $bgRange) {
          $styles =
          [
            'alignment' => [
              'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
              'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
              'color' => ['argb' => $bgRange['bgColor']]
            ]
          ];
          if(is_null($bgRange['bgColor'])) unset($styles['fill']);
          //IMPORTANT: I just unprotect some cell specified here
          if($bgRange['protection'] === false) {
            $event->sheet->getStyle($bgRange['range'])->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
          }
          // apply styles bg and more
          $event->sheet->styleCells($bgRange['range'],$styles);
        }
      }
    ];
  }
}
