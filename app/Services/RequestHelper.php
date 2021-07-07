<?php


namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class RequestHelper
{
    /**
     * @param UploadedFile $file
     * @return array
     * @throws Exception
     */
    public function readFile(UploadedFile $file): array
    {
        $tmpPath = $file->store('upload', 'public');

        try {
            $rows = self::read(Storage::path($tmpPath));
            $this->deleteTempFile($tmpPath);
            return $rows;
        } catch (Exception $e) {
            throw $e;
        }
    }

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

    /**
     * @param $tmpPath
     */
    private function deleteTempFile($tmpPath)
    {
        Storage::delete($tmpPath);
    }
}
