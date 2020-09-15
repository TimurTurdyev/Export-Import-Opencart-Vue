<?php

class ControllerBeardedExportImportLoad extends Controller
{
    private $error = array();

    public function index()
    {
        $data['title'] = $this->document->getTitle();

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['links'] = $this->getLinks();
        $data['styles'] = $this->getStyles();
        $data['scripts'] = $this->getScripts();

        $this->response->setOutput($this->load->view('bearded_export_import/index', $data));
    }

    protected function getLinks()
    {
        return [

        ];
    }

    protected function getStyles()
    {
        return [

        ];
    }

    protected function getScripts()
    {
        return [

        ];
    }
}
