<?php

class ControllerBeardedCodeProduct extends Controller
{
    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('beardedcode/product');
    }

    public function list()
    {
        $json = array();

        if (!empty($this->request->get['filter']) && $request_filter = $this->request->get['filter']) {
            $filter = array(
                'start' => 0,
                'limit' => 30
            );

            if (isset($request_filter['limit'])) {
                $filter['limit'] = $request_filter['limit'];
            }

            if (isset($request_filter['page'])) {
                $filter['start'] = ($request_filter['page'] - 1) * $filter['limit'];
            }

            $json['total'] = $this->model_beardedcode_product->total($filter);
            $json['products'] = $this->model_beardedcode_product->list($filter);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function single()
    {
        $json = array();

        if (isset($this->request->get['id']) && $id = $this->request->get['id']) {
            $json = $this->model_beardedcode_product->single($id);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
