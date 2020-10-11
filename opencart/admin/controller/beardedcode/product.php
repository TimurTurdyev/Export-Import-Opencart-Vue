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
                'limit' => 30,
                'status' => null
            );

            if (isset($request_filter['limit'])) {
                $filter['limit'] = $request_filter['limit'];
            }

            if (isset($request_filter['page'])) {
                $filter['start'] = ($request_filter['page'] - 1) * $filter['limit'];
            }

            if (!empty($request_filter['names'])) {
                $filter['names'] = $request_filter['names'];
            }

            if (!empty($request_filter['categories'])) {
                $filter['categories'] = $request_filter['categories'];
            }

            if (!empty($request_filter['percent'])) {
                $filter['percent'] = $request_filter['percent'];
            }

            if (isset($request_filter['status'])) {
                $filter['status'] = $request_filter['status'];
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
        $filter_data = array(
            'sort' => 'name',
            'order' => 'ASC'
        );
        if (isset($this->request->get['id']) && $id = $this->request->get['id']) {
            $json = $this->model_beardedcode_product->single($id);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function prices()
    {
        $this->load->model('beardedcode/update');
        if (($this->request->server['REQUEST_METHOD'] === 'POST')
            && $this->user->hasPermission('modify', 'beardedcode/product')
            && isset($this->request->server['CONTENT_TYPE'])
            && (strripos($this->request->server['CONTENT_TYPE'], 'application/json;') !== false)
            && ($request = json_decode(file_get_contents('php://input'), 1))) {

            if (is_array($request)) {
                if (!empty($request)) {

                    foreach ($request as $product) {
                        if ($product['id']) {
                            $this->model_beardedcode_update->product($product['id'], [
                                'sku' => $product['sku'],
                                'price' => $product['price'],
                                'cost' => $product['cost'],
                                'cost_percentage' => $product['cost_percentage'],
                                'status' => $product['status'],
                            ]);
                            if (!empty($product['options'])) {
                                $this->model_beardedcode_update->product_options($product['options']);
                            }
                        }
                    }
                    $json['$product_info'] = $request;
                    $json['success'] = 'Success! Products to updated';
                } else {
                    $json['error'] = 'This load data to fail';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function update()
    {
        $this->load->model('beardedcode/update');
        if (($this->request->server['REQUEST_METHOD'] === 'POST')
            && $this->user->hasPermission('modify', 'beardedcode/product')
            && isset($this->request->server['CONTENT_TYPE'])
            && (strripos($this->request->server['CONTENT_TYPE'], 'application/json;') !== false)
            && ($request = json_decode(file_get_contents('php://input'), 1))) {

            if (is_array($request)) {
                if (!empty($request['product'])) {
                    $product_info = array(
                        'product' => $request['product'],
                        'product_description' => $request['product_description'],
                        'product_to_category' => $request['product_to_category'],
                        'product_option_value' => $request['product_option_value'],
                        'product_discount' => $request['product_discount'],
                    );
                    $product_id = $product_info['product']['product_id'] ?? 0;
                    foreach ($product_info as $metod => $data) {
                        if ($product_id) {
                            $this->model_beardedcode_update->{$metod}($product_id, $data);
                        }
                    }
                    $json['success'] = 'Success! Products to updated';
                } else {
                    $json['error'] = 'This load data to fail';
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function categories()
    {
        $this->load->model('catalog/category');

        $json = array();

        $filter_data = array(
            'sort' => 'name',
            'order' => 'ASC'
        );

        $json['categories'] = $this->model_catalog_category->getCategories($filter_data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function options()
    {
        $this->load->model('catalog/option');

        $json = array();

        $filter_data = array(
            'sort' => 'name',
            'order' => 'ASC'
        );

        $json['categories'] = $this->model_catalog_option->getOptions($filter_data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function discount()
    {
        $json = array();
        $id = $this->request->get['product_id'] ?? 0;
        $result = $this->model_beardedcode_product->discount($id);

        if ($result['discount']) {
            $json['discount'] = $result['discount'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
