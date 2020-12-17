<?php namespace Crudvel\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\{FromCollection,WithHeadings,WithEvents,ShouldAutoSize};

class ExporterInterceptor implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
{
  protected $mixedCollection = null;
  protected $sheetTitle      = null;

  public function headings(): array {
    $heading = [];
    foreach ($this->mixedCollection[0] as $key => $value)
      if($value!==null)
        $heading[]=$key;

    return $heading;
  }

  public function __construct($mixedCollection=null,$sheetTitle=null){
    $this->mixedCollection = $mixedCollection;
    $this->sheetTitle      = $sheetTitle;
  }

  public function collection(){
    return $this->mixedCollection;
  }

  public function registerEvents(): array
  {
    return [
      BeforeExport::class => function(BeforeExport $event) {
        //$event->writer->getProperties()->setCreator($this->member->name . ' ' . $this->member->surname);
      },
      AfterSheet::class => function(AfterSheet $event) {
        $styleArray = [
          'font' => [
            'bold' => true,
          ],
        ];
        if(!$this->sheetTitle)
          $this->sheetTitle = $this->mixedCollection[0][count($this->mixedCollection[0])-1];
        $event->sheet->getDelegate()->setTitle($this->sheetTitle)->getStyle('1:1')->applyFromArray($styleArray);
        //$event->sheet->getDelegate()->getStyle('A1'); // Set cell A1 as selected
      },
    ];
  }
}
