<?php
require DIR_SYSTEM . 'library/beardedcode/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ControllerBeardedcodeExport extends Controller
{
    private $error = array();
    private $file_path = DIR_DOWNLOAD . 'export-products.xlsx';
    private $download_file_path = HTTPS_CATALOG . 'system/storage/download/export-products.xlsx';

    public function index()
    {
        $json = array();
        $this->load->model('beardedcode/export');

        if (($this->request->server['REQUEST_METHOD'] === 'POST')
            && $this->user->hasPermission('modify', 'beardedcode/product')
            && isset($this->request->server['CONTENT_TYPE'])
            && (strripos($this->request->server['CONTENT_TYPE'], 'application/json;') !== false)
            && ($request = json_decode(file_get_contents('php://input'), 1))) {
            if (is_array($request) && $results = $this->model_beardedcode_export->search($request)) {
                $file_generate = $this->excel($results);
                if ($file_generate) {
                    $json['download_file_path'] = $this->download_file_path;
                }
                $json['error'] = $this->error;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function excel($products)
    {

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $row = 1;
            $i = 0;

            foreach ($products[0] as $key => $value) {
                $ColumnIndex = $sheet->getCellByColumnAndRow($i += 1, $row)->getCoordinate();
                $sheet->setCellValue($ColumnIndex, $key);
            }

            $row += 1;

            foreach ($products as $product) {
                $i = 0;
                foreach ($product as $key => $value) {
                    $ColumnIndex = $sheet->getCellByColumnAndRow($i += 1, $row)->getCoordinate();
                    $sheet->setCellValue($ColumnIndex, $value ?? '');
                }
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($this->file_path);
            return true;
        } catch (Exception $error) {
            $this->error = $error;
            return null;
        }
    }

    private function array2csv(array &$array, $titles)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, $titles, ';');
        foreach ($array as $row) {
            fputcsv($df, $row, ';');
        }
        fclose($df);
        return ob_get_clean();
    }
}
