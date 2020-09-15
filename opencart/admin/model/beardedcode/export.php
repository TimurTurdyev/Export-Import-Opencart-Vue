<?php

class ModelBeardedCodeExport extends Model
{
    private $catalog_image = HTTPS_CATALOG . 'image/';
    private $tables = array(
        'product' => array(
            'p.product_id as id', 'p.model', 'p.sku', 'p.price', 'p.cost'
        ),
        'product_description' => array(
            'pd.name',
        )
    );

    public function search($products = array())
    {
        $p = implode(', ', $this->tables['product']);
        $pd = implode(', ', $this->tables['product_description']);
        $sql = "SELECT {$p}, {$pd},";
        $sql .= " p2c.category_id,";
        $sql .= " (SELECT GROUP_CONCAT(
                                category_id
                                ORDER BY category_id ASC
                                ) 
                    FROM " . DB_PREFIX . "product_to_category
                    WHERE product_id = p.product_id AND main_category = 0
                   ) as categories,";
        $sql .= " @percent := (p.price / 100) as base_percent,
                    GROUP_CONCAT(
                        100 - d.price / @percent
                        ORDER BY 
                            d.quantity ASC SEPARATOR '\r\n'
                    ) AS coefficient,
                    (
                    SELECT GROUP_CONCAT(
                        ovd.option_value_id, '=', pov.price
                        ORDER BY 
                            pov.price ASC SEPARATOR '\r\n'
                    )
                    FROM " . DB_PREFIX . "product_option_value pov
                    LEFT JOIN " . DB_PREFIX . "option_description od ON (pov.option_id = od.option_id)
                    LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id)
                    WHERE pov.product_id = p.product_id
                 ) as options";
        $sql .= " FROM " . DB_PREFIX . "product p";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_discount d ON p.product_id = d.product_id";
        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.main_category = 1";

        $products_array = [];

        if ($products) {
            foreach ($products as $product) {
                if (is_numeric($product)) {
                    $products_array[] = "p.product_id = '" . (int)$product . "'";
                }
            }
        }

        if (count($products_array)) {
            $sql .= " AND (" . implode(' OR ', $products_array) . ')';
        }

        $sql .= " GROUP BY p.product_id";
        $sql .= " ORDER BY p2c.category_id ASC, p.product_id ASC";

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function update()
    {

    }
}
