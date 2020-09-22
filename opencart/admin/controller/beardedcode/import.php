<?php

require DIR_SYSTEM . 'library/beardedcode/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class ControllerBeardedcodeImport extends Controller
{
    private $errors = [];
    public function index()
    {
        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'beardedcode/import')) {
            $json['error'] = 'Permission denied!';
        }

        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {

                // Sanitize the filename
                $filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

                // Allowed file extension types
                $allowed = array();

                $extension_allowed = preg_replace('~\r?\n~', "\n", 'xlsx');

                $filetypes = explode("\n", $extension_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = 'File extension not allowed for download';
                }

                // Allowed file mime types
                $allowed = array();

                $mime_allowed = preg_replace('~\r?\n~', "\n", 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

                $filetypes = explode("\n", $mime_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = 'File type not allowed for download';
                }

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = 'Dangerous php';
                }

                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = 'Upload error';
                }
            } else {
                $json['error'] = 'Upload error';
            }
        }

        if (!$json) {
            $file = $filename . '.' . token(32);

            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

            // Hide the uploaded file name so people can not link to it directly.
            $this->load->model('beardedcode/upload');
            $this->model_beardedcode_upload->createTable();
            $json['code'] = $this->model_beardedcode_upload->addUpload($filename, $file);
            $json['success'] = 'The file is successfully loaded';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getUpload()
    {
        $json = array();
        $this->load->model('beardedcode/upload');

        if (isset($this->request->get['code'])) {
            $code = $this->request->get['code'];
        } else {
            $code = 0;
        }

        $upload_info = $this->model_beardedcode_upload->getUploadByCode($code);

        if ($upload_info) {
            $inputFileName = DIR_UPLOAD . $upload_info['filename'];
            $spreadsheet = IOFactory::load($inputFileName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $json['code'] = $code;
            $json['catalog_image'] = $this->catalog_image;
            $json['head'] = $sheetData['1'];
            unset($sheetData['1']);
            $json['data'] = $sheetData;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function load()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] === 'POST')
            && $this->user->hasPermission('modify', 'beardedcode/import')
            && isset($this->request->server['CONTENT_TYPE'])
            && (strripos($this->request->server['CONTENT_TYPE'], 'application/json;') !== false)
            && ($request = json_decode(file_get_contents('php://input'), 1))) {
            if (is_array($request)) {
                if ($this->update($request['productsData'])) {
                    $json['success'] = 'Success! Products to updated';
                } else {
                    $json['data'] = $this->errors;
                    $json['error'] = 'This load data to fail';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
// SELECT `COLUMN_NAME`, `COLUMN_TYPE`  FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='lpack_new' AND `TABLE_NAME` = 'oc_product';
    private function update($productData)
    {
        if (!empty($productData['id'])) {
            return false;
        }

        $this->load->model('beardedcode/update');

        foreach ($productData as $key => $data) {

            $sqlData = [
                'product' => [],
                'product_description' => [],
                'product_to_category' => '',
                'product_option_value' => '',
                'product_discount' => '',
            ];

            $product_id = 0;

            foreach ($data as $name => $value) {
                if ($name === 'product_id') {
                    $product_id = $value;
                    continue;
                }
                if (isset($sqlData[$name])) {
                    $sqlData[$name] = $value;
                } elseif($name === 'name') {
                    $sqlData['product_description'][$name] = $value;
                } else {
                    $sqlData['product'][$name] = $value;
                }
            }
            foreach ($sqlData as $metod => $data) {
                if(!$this->model_beardedcode_update->{$metod}($product_id, $data)) {
                    $this->errors[$product_id] = $data;
                }
            }
        }
        return !$this->errors;
    }
}
