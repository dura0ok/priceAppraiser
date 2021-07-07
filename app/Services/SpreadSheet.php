<?php


namespace App\Services;


use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SpreadSheet
{
    public static $defaultStyleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_MEDIUM,
                'color' => ['rgb' => '000000'],
            ],
        ]
    ];



    /**
     * @param $path
     * @return array
     * @throws Exception
     */
    public static function read($path): array
    {
        try {
            $reader = IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
        } catch (Exception $e) {
            throw $e;
        }
        return $reader->load($path)->getActiveSheet()->toArray();
    }

    public static function getAlphabet(): array
    {
        $letters = []; $counter = 0;
        foreach(range('a','z') as $item){
            $letters[$counter] = $item;
            $counter++;
        }
        return $letters;
    }
    public function findColumnIndexes($raw): array
    {
        $columns = [
            "articul" => $raw[0],
            "name" => $raw[1],
            "description" => $raw[2],
            "price" => $raw[3],
        ];
        $alphabet = self::getAlphabet();
        foreach ($columns as $k => $v){
            $columns[$k] = array_search($v, $alphabet, true);
        }
        return $columns;
    }

    public function generateRowValues($row, $columns): array
    {
        $values = [];
        foreach ($columns as $k => $v){
            if(!isset($row[$v])){
                continue;
            }
            $values[$k] = $row[$v];
        }
        return $values;
    }

    public function fillData(Worksheet $sheet, array $data, array $styles){
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => array('argb' => '00000000'),
                ),
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        );
        $strokeCounter = 1;
        $alphabet = self::getAlphabet();

        foreach ($data as $product){
            for ($i = 0; $i < count($product); $i++){
                $sheet->getColumnDimension($alphabet[$i])->setAutoSize(true);
                $sheet->setCellValue($alphabet[$i].$strokeCounter, $product[$i]);
            }
            $strokeCounter++;
        }
        foreach ($styles as $k => $v){
            $sheet->getStyle($k)->applyFromArray($v);

        }
    }

    /**
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet
     * @param string $folder
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, string $folder): string
    {
        $writer = new Xlsx($spreadsheet);
        $fpath = Storage::path($folder)."/".md5(microtime()).".xlsx";
        try {
            $writer->save($fpath);
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            throw $e;
        }
        return $fpath;
    }



}
