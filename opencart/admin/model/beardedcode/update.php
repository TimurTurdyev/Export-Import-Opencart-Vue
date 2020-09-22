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

    public function product_description($product_id, $data)
    {
        $SQL = "UPDATE `" . DB_PREFIX . "product_description` SET ";
        $VALUES = [];

        foreach ($data as $name => $value) {
            array_push($VALUES, "{$name}= '{$value}'");
        }

        if (count($VALUES)) {
            $SQL .= implode(', ', $VALUES) . ' WHERE product_id = ' . $product_id;
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
        $data = explode(',', $data);
        foreach ($data as $index => $value) {
            array_push($VALUES, "('" . (int)$product_id . "', '" . (int)$value . "', '" . (!$index ? 1 : 0) . "')");
        }

        if (count($VALUES)) {
            $SQL .= implode(', ', $VALUES);
            return $this->db->query($SQL);
        }
        return false;
    }

    // INSERT INTO `oc_product_discount` (`product_discount_id`, `product_id`, `customer_group_id`, `quantity`, `priority`, `price`, `date_start`, `date_end`) VALUES (...)
    public function product_discount($product_id, $data)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_discount` WHERE product_id = " . $product_id);

        $SQL = "INSERT INTO `" . DB_PREFIX . "product_discount`";
        $SQL .= " (product_id, customer_group_id, quantity, priority, price) VALUES ";

        $VALUES = [];
        $data = explode('\r\n', $data);
        foreach ($data as $index => $value) {
            array_push($VALUES, "('" . (int)$product_id . "', '1', '1000', '" . $index . "', '" . (float)$value . "')");
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
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_option` WHERE product_id = " . $product_id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_option_value` WHERE product_id = " . $product_id);

        $data = explode('\r\n', $data);

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
