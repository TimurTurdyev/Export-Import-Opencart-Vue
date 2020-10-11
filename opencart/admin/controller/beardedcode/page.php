<?php
class ControllerBeardedcodePage extends Controller
{
    public function index()
    {
        if (file_exists(DIR_APPLICATION . 'view/javascript/bearded_export_import/index.html')) {
            $page = file_get_contents(DIR_APPLICATION . 'view/javascript/bearded_export_import/index.html');
            /*$page = str_replace('href=', 'href=' . HTTPS_SERVER . 'view/javascript/bearded_export_import', $page);
            $page = str_replace('src=', 'src=' . HTTPS_SERVER . 'view/javascript/bearded_export_import', $page);*/
            $page = str_replace('</head>', '<base href="' . HTTPS_SERVER . '"/></head>', $page);
            $this->response->setOutput($page);
        }
    }
}
