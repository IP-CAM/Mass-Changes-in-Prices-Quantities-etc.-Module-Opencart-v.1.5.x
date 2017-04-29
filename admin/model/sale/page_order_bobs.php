<?php

class ModelSalePageOrderBobs extends Model
{

    public function setParameters($array_post_parameter)
    {

        $get_order_id=null;
        if ($array_post_parameter['get_order_id'] != '') {
            $get_order_id=$array_post_parameter['get_order_id'];
        }
        $sql = "UPDATE `" . DB_PREFIX . "page_order_bobs_parameters` SET
                `parameters_id` = '1', " .
            "`get_order_id` = " . (int)$get_order_id . ", " .
            "`currency_code_check` = '" . (int)$array_post_parameter['currency_code_check'] . "', " .
            "`option_client_percent_default` = " . (int)$array_post_parameter['option_client_percent_default'] . ", " .
            "`option_client_percent` = '" . $this->db->escape($array_post_parameter['option_client_percent']) . "', " .
            "`pay2pay_check` = '" . (int)$array_post_parameter['pay2pay_check'] . "', " .
            "`pay2pay_identifier_shop` = '" . $this->db->escape($array_post_parameter['pay2pay_identifier_shop']) . "', " .
            "`pay2pay_key_secret` = '" . $this->db->escape($array_post_parameter['pay2pay_key_secret']) . "', " .
            "`pay2pay_test_mode` = '" . (int)$array_post_parameter['pay2pay_test_mode'] . "', " .
            "`robokassa_check` = '" . (int)$array_post_parameter['robokassa_check'] . "', " .
            "`robokassa_identifier_shop` = '" . $this->db->escape($array_post_parameter['robokassa_identifier_shop']) . "', " .
            "`robokassa_key_secret` = '" . $this->db->escape($array_post_parameter['robokassa_key_secret']) . "', " .
            "`robokassa_test_mode` = " . (int)$array_post_parameter['robokassa_test_mode'] . ", " .
            "`interkassa_check` = '" . (int)$array_post_parameter['interkassa_check'] . "', " .
            "`interkassa_identifier_shop` = '" . $this->db->escape($array_post_parameter['interkassa_identifier_shop']) . "', " .
            "`interkassa_test_mode` = " . (int)$array_post_parameter['interkassa_test_mode'] . ", " .
            "`alter_payment_check` = ".(int)$array_post_parameter['alter_payment_check'] . ", " .
            "`alter_payment_text` = '" . $this->db->escape($array_post_parameter['alter_payment_text']) . "' " .
            " WHERE `parameters_id` =1";

        $this->db->query($sql); //Create datbase line
    }

    public function getParameters()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_order_bobs_parameters  WHERE parameters_id = 1");

        return $query->row;
    }

    public function suitableUrlAliasNameAndId($name_page, $page_id)
    {
        $sql = "
        SELECT * FROM `" . DB_PREFIX . "url_alias`
        WHERE `keyword`= '" . $this->db->escape($name_page) . "'
        AND `query`='page_order_bobs_id=" . (int)$page_id . "'";
        $obj_sql = $this->db->query($sql);
        if ($obj_sql->num_rows == 0) {
            return false;
        } else {
            return true;

        }
    }

    public function findUrlAliasName($name_page)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `keyword` LIKE '" . $this->db->escape($name_page) . "'";
        $obj_sql = $this->db->query($sql);
        if ($obj_sql->num_rows == 0) {
            return false;
        } else {
            return true;

        }
    }

    //return page description
    public function getPageByOrder($order_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "page_order_bobs_description` WHERE `order_id` LIKE " . (int)$order_id;
        $obj_sql_page = $this->db->query($sql);
        if ($obj_sql_page->num_rows == 0) {
            return false;
        } else {
            $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'page_order_bobs_id=" . (int)$obj_sql_page->row['page_id'] . "'";
            $obj_sql = $this->db->query($sql);
            $obj_sql_page->row['name_page'] = $obj_sql->row['keyword'];
            return $obj_sql_page->row;

        }
    }

    //return page description
    public function getPageByPage($page_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "page_order_bobs_description` WHERE `page_id` LIKE " . (int)$page_id;
        $obj_sql_page = $this->db->query($sql);
        if ($obj_sql_page->num_rows == 0) {
            return false;
        } else {
            $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'page_order_bobs_id=" . (int)$page_id . "'";
            $obj_sql = $this->db->query($sql);
            $obj_sql_page->row['name_page'] = $obj_sql->row['keyword'];
            return $obj_sql_page->row;

        }
    }

    public function getNamePageByPage($page_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'page_order_bobs_id=" . (int)$page_id . "'";
        $obj_sql = $this->db->query($sql);
        return $obj_sql->row['keyword'];
    }


    public function addPage($array_post_parameter)
    {

        $max_id = $this->db->query("SELECT MAX(`page_id`) FROM " . DB_PREFIX . "page_order_bobs");
        $max_id = $max_id->row['MAX(`page_id`)'];
        if ($max_id == null) {
            $max_id = 0;
        }
        $page_id = (int)$max_id + 1; //following line

        $sql = "REPLACE INTO `" . DB_PREFIX . "page_order_bobs`
        (`page_id`, `bottom`, `status`, `store_id`) VALUES (".
        (int)$page_id.", 0, 1, 0)";
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            return false;
        }

        if (!$this->setPageDescription($array_post_parameter, $page_id)) {
            return false;
        }

        // id url alias
        $max_id = $this->db->query("SELECT MAX(`url_alias_id`) FROM " . DB_PREFIX . "url_alias");
        $max_id = $max_id->row['MAX(`url_alias_id`)'];
        $url_alias_id = (int)$max_id + 1; //following line
        $sql = "REPLACE INTO `" . DB_PREFIX . "url_alias`
        (`url_alias_id`, `query`, `keyword`) VALUES ('" .
            (int)$url_alias_id . "', '" .
            "page_order_bobs_id=" . (int)$page_id . "', '" .
            $this->db->escape($array_post_parameter['name_page']) . "')";
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }


    public function updatePage($array_post_parameter, $page_id)
    {

        $sql = "REPLACE INTO `" . DB_PREFIX . "page_order_bobs`
        (`page_id`, `bottom`, `status`, `store_id`) VALUES (".
            (int)$page_id.", 0, 1, 0)";
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            return false;
        }

        if (!$this->setPageDescription($array_post_parameter, $page_id)) {
            return false;
        }


        $sql = "UPDATE `" . DB_PREFIX . "url_alias`
                SET `keyword` = '" . $this->db->escape($array_post_parameter['name_page']) . "'
                WHERE `query` = 'page_order_bobs_id=" . (int)$page_id . "'";
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }


    public function getTotalOrderPageCount()
    {
        $query = $this->db->query("SELECT COUNT(*) FROM " . DB_PREFIX . "page_order_bobs");
        return $query->row['COUNT(*)'];
    }

    public function getPagesOrder($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "page_order_bobs op LEFT JOIN " . DB_PREFIX . "page_order_bobs_description opd ON (op.page_id = opd.page_id) WHERE opd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sort_data = array(
                'opd.page_id',
                'opd.order_id'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY opd.page_id";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);
            $pages=Array();
            foreach($query->rows as $key=>$page)
            {
                $pages[$key]=$page;

                $sql="SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query='page_order_bobs_id=".(int)$page['page_id']."'";
                $query = $this->db->query($sql);
                $pages[$key]['keyword']=$query->row['keyword'];
            }

            return $pages;
        } else {
            $pages_order = $this->cache->get('page_order_bobs.' . (int)$this->config->get('config_language_id'));

            if (!$pages_order) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_order_bobs op LEFT JOIN " . DB_PREFIX . "page_order_bobs_description opd ON (op.page_id = opd.page_id) WHERE opd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY opd.page_id");

                $pages_order = $query->rows;
                $pages=Arrey();
                foreach($pages_order as $key=>$page)
                {
                    $pages[$key]=$page;
                    $pages[$key]['query']='	page_order_bobs_id='.$page['page_id'];
                }

                $this->cache->set('page_order_bobs.' . (int)$this->config->get('config_language_id'), $pages_order);
            }

            return $pages_order;
        }
    }

    public function deleteOrderPage($page_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "page_order_bobs WHERE page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "page_order_bobs_description WHERE page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'page_order_bobs_id=" . (int)$page_id . "'");

        $this->cache->delete('information');


    }


    private function setPageDescription($array_post_parameter, $page_id)
    {
        $sql = "REPLACE INTO `" . DB_PREFIX . "page_order_bobs_description`
        (`page_id`,
        `order_id`,
        `order_alter_check`,
        `order_alter_id`,
        `language_id`,
        `currency_code_check`,
        `currency_code`,
        `price_total`,
        `price`,
        `per_cent_of_all`,
        `option_client_percent_default`,
        `option_client_percent`,
        `receiver_of_product`,
        `description_order`,
        `delivery_address`,
        `delivery_method`,
        `notes`,
        `pay2pay_check`,
        `pay2pay_identifier_shop`,
        `pay2pay_key_secret`,
        `pay2pay_test_mode`,
        `robokassa_check`,
        `robokassa_identifier_shop`,
        `robokassa_key_secret`,
        `robokassa_test_mode`,
        `interkassa_check`,
        `interkassa_identifier_shop`,
        `interkassa_test_mode`,
        `alter_payment_check`,
        `alter_payment_text`,
        `link_pay2pay`,
        `link_robokassa`,
        `link_interkassa`
        ) VALUES (" .
            (int)$page_id . ", " .
            (int)$array_post_parameter['order_id'] . ", " .
            (int)$array_post_parameter['order_alter_check'] . ", " .
            (int)$array_post_parameter['order_alter_id'] . ", " .
            (int)$array_post_parameter['language_id'] . ", " .
            (int)$array_post_parameter['currency_code_check'] . ", '" .
            $this->db->escape($array_post_parameter['currency_code']) . "', " .
            (float)$array_post_parameter['price_total'] . ", " .
            (float)$array_post_parameter['price'] . ", " .
            (int)$array_post_parameter['per_cent_of_all'] . ", " .
            (int)$array_post_parameter['option_client_percent_default'] . ", '" .
            $this->db->escape($array_post_parameter['option_client_percent']) . "', '" .
            $this->db->escape($array_post_parameter['receiver_of_product']) . "', '" .
            $this->db->escape($array_post_parameter['description_order']) . "', '" .
            $this->db->escape($array_post_parameter['delivery_address']) . "', '" .
            $this->db->escape($array_post_parameter['delivery_method']) . "', '" .
            $this->db->escape($array_post_parameter['notes']) . "', " .
            $this->db->escape($array_post_parameter['pay2pay_check']) . ", '" .
            $this->db->escape($array_post_parameter['pay2pay_identifier_shop']) . "', '" .
            $this->db->escape($array_post_parameter['pay2pay_key_secret']) . "', '" .
            $this->db->escape($array_post_parameter['pay2pay_test_mode']) . "', '" .
            $this->db->escape($array_post_parameter['robokassa_check']) . "', '" .
            $this->db->escape($array_post_parameter['robokassa_identifier_shop']) . "', '" .
            $this->db->escape($array_post_parameter['robokassa_key_secret']) . "', " .
            $this->db->escape($array_post_parameter['robokassa_test_mode']) . ", '" .
            $this->db->escape($array_post_parameter['interkassa_check']) . "', '" .
            $this->db->escape($array_post_parameter['interkassa_identifier_shop']) . "', " .
            $this->db->escape($array_post_parameter['interkassa_test_mode']) . ", " .
            $this->db->escape($array_post_parameter['alter_payment_check']) . ", '" .
            $this->db->escape($array_post_parameter['alter_payment_text']) . "', '" .
            $this->db->escape($array_post_parameter['link_pay2pay']) . "', '" .
            $this->db->escape($array_post_parameter['link_robokassa']) . "', '" .
            $this->db->escape($array_post_parameter['link_interkassa']) . "')";
        try {
            $this->db->query($sql);

        } catch (Exception $e) {
            return false;
        }
        return true;
    }

}


?>