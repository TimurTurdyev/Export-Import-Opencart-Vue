<?php

class ModelBeardedCodeProduct extends Model
{
    private $catalog_image = HTTPS_CATALOG . 'image/';

    public function single($id)
    {
        $this->load->model('catalog/product');
        $product = $this->model_catalog_product->getProduct($id);
        $product['catalog_image'] = $this->catalog_image;
        $product['description'] = html_entity_decode($product['description']);
        return $product;
        /*return array(
            'catalog_image' => $this->catalog_image,
            'product' => $product,
            'product_attribute' => $this->model_catalog_product->getProductAttributes($id),
            'product_description' => $this->model_catalog_product->getProductDescriptions($id),
            'product_discount' => $this->model_catalog_product->getProductDiscounts($id),
            'product_filter' => $this->model_catalog_product->getProductFilters($id),
            'product_image' => $this->model_catalog_product->getProductImages($id),
            'product_option' => $this->model_catalog_product->getProductOptions($id),
            'product_related' => $this->model_catalog_product->getProductRelated($id),
            'product_reward' => $this->model_catalog_product->getProductRewards($id),
            'product_special' => $this->model_catalog_product->getProductSpecials($id),
            'product_category' => $this->model_catalog_product->getProductCategories($id),
            'product_download' => $this->model_catalog_product->getProductDownloads($id),
            'product_layout' => $this->model_catalog_product->getProductLayouts($id),
            'product_store' => $this->model_catalog_product->getProductStores($id),
            'product_recurrings' => $this->model_catalog_product->getRecurrings($id),
            'main_category_id' => $this->model_catalog_product->getProductMainCategoryId($id),
        );*/
    }

    public function list($filter = array())
    {
        $sql = "SELECT p.product_id as id, CONCAT('{$this->catalog_image}', IF(p.image != '', p.image, 'no_image.png')) as image, p.sku, p.price, pd.name as title FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

        if (!empty($filter['category'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
        }

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($filter['title'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($filter['title']) . "%'";
        }

        if (!empty($filter['model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($filter['model']) . "%'";
        }

        if (isset($filter['price']) && !is_null($filter['price'])) {
            $sql .= " AND p.price LIKE '" . $this->db->escape($filter['price']) . "%'";
        }

        if (isset($filter['quantity']) && !is_null($filter['quantity'])) {
            $sql .= " AND p.quantity = '" . (int)$filter['quantity'] . "'";
        }

        if (isset($filter['status']) && !is_null($filter['status'])) {
            $sql .= " AND p.status = '" . (int)$filter['status'] . "'";
        }

        if (isset($filter['image']) && !is_null($filter['image'])) {
            if ($filter['image'] == 1) {
                $sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
            } else {
                $sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
            }
        }

        if (!empty($filter['category'])) {
            $sql .= " AND p2c.category_id = '" . (int)$filter['category'] . "'";
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

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function total($filter = array())
    {
        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($filter['name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($filter['name']) . "%'";
        }

        if (!empty($filter['model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($filter['model']) . "%'";
        }

        if (isset($filter['price']) && !is_null($filter['price'])) {
            $sql .= " AND p.price LIKE '" . $this->db->escape($filter['price']) . "%'";
        }

        if (isset($filter['quantity']) && !is_null($filter['quantity'])) {
            $sql .= " AND p.quantity = '" . (int)$filter['quantity'] . "'";
        }

        if (isset($filter['category']) && !is_null($filter['category'])) {
            $sql .= " AND p2c.category_id = '" . (int)$filter['category'] . "'";
        }

        if (isset($filter['status']) && !is_null($filter['status'])) {
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
