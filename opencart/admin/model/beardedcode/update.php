<?php

class ModelBeardedCodeUpdate extends Model
{
    public function product($product_id, $data)
    {
        $SQL = "UPDATE `" . DB_PREFIX . "product` SET ";
        $VALUES = [];

        foreach ($data as $name => $value) {
            array_push($VALUES, "{$name}= '{$value}'");
        }

        if (count($VALUES)) {
            $SQL .= implode(', ', $VALUES) . " WHERE product_id='{$product_id}'";

            return $this->db->query($SQL);
        }
        return false;
    }

    public function product_description($product_id, $data, $language_id = 1)
    {
        $SQL = "UPDATE `" . DB_PREFIX . "product_description` SET ";
        $VALUES = [];

        foreach ($data as $name => $value) {
            array_push($VALUES, "{$name}= '{$value}'");
        }

        if (count($VALUES)) {
            $SQL .= implode(', ', $VALUES) . ' WHERE product_id = ' . $product_id . ' AND language_id = ' . $language_id;
            return $this->db->query($SQL);
        }
        return false;
    }

    // INSERT INTO `oc_product_to_category`(`product_id`, `category_id`, `main_category`) VALUES (...)
    public function product_to_category($product_id, $data)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = " . $product_id);

        $SQL = "REPLACE INTO `" . DB_PREFIX . "product_to_category`";
        $SQL .= " (product_id, category_id, main_category) VALUES ";

        $VALUES = [];
        if (is_string($data) || is_numeric($data)) {
            $data = explode(',', $data);
        }
        foreach ($data as $index => $value) {
            array_push($VALUES, "('" . (int)$product_id . "', '" . (int)$value . "', '" . (!$index ? 1 : 0) . "')");
        }

        if (count($VALUES)) {
            $SQL .= implode(', ', $VALUES);
            return $this->db->query($SQL);
        }
        return false;
    }

    public function product_options($options = array())
    {
        /*        $options = $this->db->query("SELECT po.product_option_id, od.name, od.option_id
                                    FROM " . DB_PREFIX . "product_option po
                                    LEFT JOIN " . DB_PREFIX . "option_description od ON po.option_id = od.option_id AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'
                                    WHERE po.product_id = '" . (int)$id . "'
                                    ORDER BY po.option_id ASC")->rows;*/
        foreach ($options as $option) {
            /*$this->db->query("SELECT *
                                        FROM " . DB_PREFIX . "product_option_value pov
                                        LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON pov.option_value_id = ovd.option_value_id AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                                        WHERE pov.product_option_id = '" . (int)$option['product_option_id'] . "'")->rows;*/
            if (!empty($option['values'])) {
                foreach ($option['values'] as $value) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET price = '" . (float)$value['price'] . "', cost = '" . (float)$value['cost'] . "', price_prefix = '" . $this->db->escape($value['price_prefix']) . "', quantity = '" . (int)$value['quantity'] . "' WHERE product_option_value_id = '" . (int)$value['product_option_value_id'] . "'");
                }
            }
        }
    }

    // INSERT INTO `oc_product_discount` (`product_discount_id`, `product_id`, `customer_group_id`, `quantity`, `priority`, `price`, `date_start`, `date_end`) VALUES (...)
    public function product_discount($product_id, $data)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_discount` WHERE product_id = " . $product_id);
        $SQL = "INSERT INTO `" . DB_PREFIX . "product_discount`";
        $SQL .= " (product_id, customer_group_id, quantity, priority, price) VALUES ";

        $VALUES = [];

        $data = explode(PHP_EOL, $data);
        foreach ($data as $index => $value) {
            list($quantity, $price) = explode('>', $value);
            if (isset($quantity) && isset($price)) {
                array_push($VALUES, "('" . (int)$product_id . "', '1', '" . (int)$quantity . "', '" . $index . "', '" . (float)$price . "')");
            }
        }

        if (count($VALUES)) {

            $SQL .= implode(', ', $VALUES);

            return $this->db->query($SQL);
        }
        return false;
    }

    // INSERT INTO `oc_product_option` SET `product_id` = ?, `option_id` = ?, `value` = '', `required` = 1;
    // INSERT INTO `oc_product_option_value` SET `product_option_id` = ?, `product_id` = ?, `option_id` = ?, `option_value_id` = ?, `sku` = '', `quantity` = ?, `subtract` = 0, `price` = ?, `cost` = '0.0000', `costing_method` = 0, `cost_amount` = '', `cost_prefix` = ?, `price_prefix` = ?, `points` = 0, `points_prefix` = ?, `weight` = 0, `weight_prefix` = ?
    public function product_option_value($product_id, $data)
    {
        if (!is_string($data) && !$data) {
            return;
        }
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_option` WHERE product_id = " . $product_id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_option_value` WHERE product_id = " . $product_id);

        $data = explode(PHP_EOL, $data);

        $pattern = '/\[(\d{0,})\].{0,}([\=\+])(\d{0,}\.?\d{0,})/';
        $replacement = '$1|$3|$2';

        foreach ($data as $index => $value) {
            $value = explode('|', preg_replace($pattern, $replacement, trim($value)));

            if (count($value) == 3 && $option_query = $this->db->query("SELECT `" . DB_PREFIX . "option_value` WHERE option_value_id = '" . (int)$value[0] . "'")->row) {

                $this->db->query("REPLACE INTO `" . DB_PREFIX . "product_option` SET `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$option_query->row['option_id'] . "', `value` = '', `required` = 1");

                $product_option_id = $this->db->getLastId();

                $this->db->query("REPLACE INTO `" . DB_PREFIX . "product_option_value` SET `product_option_id` = '" . $product_option_id . "', `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$option_query->row['option_id'] . "', `option_value_id` = '" . $value[0] . "', `sku` = '', `quantity` = 99, `subtract` = 0, `price` = '" . (float)$value[1] . "', `cost` = '0.0000', `costing_method` = 0, `cost_amount` = '', `cost_prefix` = '" . $value[2] . "', `price_prefix` = '" . $value[2] . "', `points` = 0, `points_prefix` = '+', `weight` = 0, `weight_prefix` = '+'");
            }
        }

        return true;
    }
}
