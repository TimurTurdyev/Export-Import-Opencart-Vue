<?php

class ModelBeardedCodeProduct extends Model
{
    private $catalog_image = HTTPS_CATALOG . 'image/';

    public function single($id)
    {
        $this->load->model('catalog/product');
        $product_info = $this->model_catalog_product->getProduct($id);
        $product = array_intersect_key($product_info,
            array(
                'product_id' => '',
                'model' => '',
                'sku' => '',
                'cost' => '',
                'price' => '',
                'cost_percentage' => '',
                'quantity' => '',
                'minimum' => '',
                'status' => '',
            )
        );
        $description = array_intersect_key($product_info,
            array(
                'name' => '',
                'meta_h1' => '',
                'meta_title' => '',
                'meta_description' => '',
                'description' => '',
            )
        );
        $description['description'] = html_entity_decode($description['description']);
        $discount = $this->discount($id);
        return array(
            'catalog_image' => $this->catalog_image,
            'product' => $product,
            'product_description' => $description,
            'product_attribute' => $this->model_catalog_product->getProductAttributes($id),
            'product_discount' => $discount['discount'] ?? null,
            //'product_filter' => $this->model_catalog_product->getProductFilters($id),
            'product_image' => $this->model_catalog_product->getProductImages($id),
            'product_option' => $this->model_catalog_product->getProductOptions($id),
            //'product_related' => $this->model_catalog_product->getProductRelated($id),
            //'product_reward' => $this->model_catalog_product->getProductRewards($id),
            'product_special' => $this->model_catalog_product->getProductSpecials($id),
            'product_to_category' => $this->model_catalog_product->getProductCategories($id),
            //'product_download' => $this->model_catalog_product->getProductDownloads($id),
            //'product_layout' => $this->model_catalog_product->getProductLayouts($id),
            //'product_store' => $this->model_catalog_product->getProductStores($id),
            //'product_recurrings' => $this->model_catalog_product->getRecurrings($id),
            'main_category_id' => $this->model_catalog_product->getProductMainCategoryId($id),
        );
    }

    public function discount($id)
    {
        return $this->db->query("SELECT 
                                    GROUP_CONCAT(
                                    d.quantity, '>', 100 - d.price / (p.price / 100)
                                    ORDER BY 
                                        d.quantity ASC SEPARATOR '\r\n') as discount
                                FROM `" . DB_PREFIX . "product` p
                                LEFT JOIN `" . DB_PREFIX . "product_discount` d ON p.product_id = d.product_id
                                WHERE p.product_id = '" . (int)$id . "'")->row;
    }

    public function list($filter = array())
    {
        $sql = "SELECT p.product_id as id, 
                        CONCAT('{$this->catalog_image}', IF(p.image != '', p.image, 'no_image.png')) as image, 
                        p.sku, p.price, p.cost, p.cost_percentage, p.status, pd.name as title
                FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

        if (!empty($filter['categories'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
        }

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($filter['names'])) {
            $names = [];
            foreach ($filter['names'] as $name) {
                array_push($names, "pd.name LIKE '%" . $this->db->escape($name) . "%'");
            }
            if (count($names)) {
                $sql .= " AND (" . implode(' OR ', $names) . ")";
            }
        }

        if (!empty($filter['model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($filter['model']) . "%'";
        }

        if (isset($filter['price']) && !is_null($filter['price'])) {
            $sql .= " AND p.price LIKE '" . $this->db->escape($filter['price']) . "%'";
        }

        if (!empty($filter['percent']) && is_array($filter['percent'])) {
            $sql .= " AND (p.cost_percentage BETWEEN '" . (int)$filter['percent']['min'] . "' AND '" . (int)$filter['percent']['max'] . "')";
        }

        if (isset($filter['quantity']) && !is_null($filter['quantity'])) {
            $sql .= " AND p.quantity = '" . (int)$filter['quantity'] . "'";
        }

        if (isset($filter['status']) && $filter['status'] != '') {
            $sql .= " AND p.status = '" . (int)$filter['status'] . "'";
        }

        if (isset($filter['image']) && !is_null($filter['image'])) {
            if ($filter['image'] == 1) {
                $sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
            } else {
                $sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
            }
        }

        if (!empty($filter['categories'])) {
            $categories = [];
            foreach ($filter['categories'] as $category_id) {
                array_push($categories, 'p2c.category_id=' . (int)$category_id);
            }
            if (count($categories)) {
                $sql .= " AND (" . implode(' OR ', $categories) . ")";
            }
        }
        $sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'p.price',
            'p.quantity',
            'p.status',
            'p.sort_order'
        );

        if (isset($filter['sort']) && in_array($filter['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $filter['sort'];
        } else {
            $sql .= " ORDER BY p.product_id";
        }

        if (isset($filter['order']) && ($filter['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($filter['start']) || isset($filter['limit'])) {
            if ($filter['start'] < 0) {
                $filter['start'] = 0;
            }

            if ($filter['limit'] < 1) {
                $filter['limit'] = 30;
            }

            $sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
        }
//        print_r($sql);

        $query = $this->db->query($sql)->rows;
        $products = array();

        foreach ($query as $row) {
            $products[$row['id']] = $row;
            $products[$row['id']]['options'] = $this->getProductOptions($row['id']);
        }

        return $products;
    }

    public function getProductOptions($id)
    {
        $options = $this->db->query("SELECT po.product_option_id, od.name, od.option_id
                            FROM " . DB_PREFIX . "product_option po
                            LEFT JOIN " . DB_PREFIX . "option_description od ON po.option_id = od.option_id AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'
                            WHERE po.product_id = '" . (int)$id . "'
                            ORDER BY po.option_id ASC")->rows;
        foreach ($options as &$option) {
            $option['values'] = $this->db->query("SELECT *
                                        FROM " . DB_PREFIX . "product_option_value pov
                                        LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON pov.option_value_id = ovd.option_value_id AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                                        WHERE pov.product_option_id = '" . (int)$option['product_option_id'] . "'")->rows;
        }

        return $options;
    }

    public function total($filter = array())
    {
        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

        if (!empty($filter['categories'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
        }

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($filter['names'])) {
            $names = [];
            foreach ($filter['names'] as $name) {
                array_push($names, "pd.name LIKE '%" . $this->db->escape($name) . "%'");
            }
            if (count($names)) {
                $sql .= " AND (" . implode(' OR ', $names) . ")";
            }
        }

        if (!empty($filter['model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($filter['model']) . "%'";
        }

        if (isset($filter['price']) && !is_null($filter['price'])) {
            $sql .= " AND p.price LIKE '" . $this->db->escape($filter['price']) . "%'";
        }

        if (!empty($filter['percent']) && is_array($filter['percent'])) {
            $sql .= " AND (p.cost_percentage BETWEEN '" . (int)$filter['percent']['min'] . "' AND '" . (int)$filter['percent']['max'] . "')";
        }

        if (isset($filter['quantity']) && !is_null($filter['quantity'])) {
            $sql .= " AND p.quantity = '" . (int)$filter['quantity'] . "'";
        }

        if (!empty($filter['categories'])) {
            $categories = [];
            foreach ($filter['categories'] as $category_id) {
                array_push($categories, 'p2c.category_id=' . (int)$category_id);
            }
            if (count($categories)) {
                $sql .= " AND (" . implode(' OR ', $categories) . ")";
            }
        }

        if (isset($filter['status']) && $filter['status'] != '') {
            $sql .= " AND p.status = '" . (int)$filter['status'] . "'";
        }

        if (isset($filter['image']) && !is_null($filter['image'])) {
            if ($filter['image'] == 1) {
                $sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
            } else {
                $sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
            }
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
