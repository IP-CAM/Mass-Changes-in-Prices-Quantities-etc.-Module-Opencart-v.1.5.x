<?php

class ModelSalePageOrderBobs extends Model
{

    /**
     * Save parameters page
     * @param array $array_page
     * @author  Bobs
     */
    public function setParameters($array_page)
    {

        $get_order_id = null;
        if ($array_page['get_order_id'] != '') {
            $get_order_id = $array_page['get_order_id'];
        }
        $sql = "REPLACE INTO `" . DB_PREFIX . "page_order_bobs_parameters` SET " .
            "`parameters_id` = 1, " .
            "`get_order_id` = " . (int)$get_order_id . ", " .
            "`order_site_check` = " . (int)$array_page['order_site_check'] . ", " .
            "`order_site_id` = " . (int)$array_page['order_site_id'] . ", " .
            "`currency_code` = '" . $this->db->escape($array_page['currency_code']) . "', " .
            "`currency_code_check` = " . (int)$array_page['currency_code_check'] . ", " .
            "`type_of_presentation` = " . (int)$array_page['type_of_presentation'] . ", " .
            "`price` = '" . $this->db->escape($array_page['price']) . "', " .
            "`receiver_of_product` = '" . $this->db->escape($array_page['receiver_of_product']) . "', " .
            "`description_order` = '" . $this->db->escape($array_page['description_order']) . "', " .
            "`delivery_address` = '" . $this->db->escape($array_page['delivery_address']) . "', " .
            "`delivery_method` = '" . $this->db->escape($array_page['delivery_method']) . "', " .
            "`notes` = '" . $this->db->escape($array_page['notes']) . "', " .
            "`pay2pay_check` = '" . (int)$array_page['pay2pay_check'] . "', " .
            "`pay2pay_identifier_shop` = '" . $this->db->escape($array_page['pay2pay_identifier_shop']) . "', " .
            "`pay2pay_key_secret` = '" . $this->db->escape($array_page['pay2pay_key_secret']) . "', " .
            "`pay2pay_test_mode` = '" . (int)$array_page['pay2pay_test_mode'] . "', " .
            "`robokassa_check` = '" . (int)$array_page['robokassa_check'] . "', " .
            "`robokassa_identifier_shop` = '" . $this->db->escape($array_page['robokassa_identifier_shop']) . "', " .
            "`robokassa_key_secret` = '" . $this->db->escape($array_page['robokassa_key_secret']) . "', " .
            "`robokassa_test_mode` = " . (int)$array_page['robokassa_test_mode'] . ", " .
            "`interkassa_check` = '" . (int)$array_page['interkassa_check'] . "', " .
            "`interkassa_identifier_shop` = '" . $this->db->escape($array_page['interkassa_identifier_shop']) . "', " .
            "`interkassa_test_mode` = " . (int)$array_page['interkassa_test_mode'] . ", " .
            "`alter_payment_check` = " . (int)$array_page['alter_payment_check'] . ", " .
            "`alter_payment_text` = '" . $this->db->escape($array_page['alter_payment_text']) . "', " .
            "`one_price_total` = '" . $this->db->escape($array_page['one_price_total']) . "', " .
            "`one_percent` = " . (int)$array_page['one_percent'] . ", " .
            "`several_percent_default` = '" . $this->db->escape($array_page['several_percent_default']) . "', " .
            "`several_percent` = '" . $this->db->escape($array_page['several_percent']) . "'";
        $this->db->query($sql);
    }

    public function getParameters()
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_order_bobs_parameters`  ORDER BY  parameters_id DESC LIMIT 1");
        return $query->row;
    }


    /**
     * Return default parameters
     * @return mixed
     * @author  Bobs
     */
    public function getDefaultParameters()
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_order_bobs_parameters`  WHERE parameters_id = 0");
        return $query->row;
    }

    /**
     * Return Root default page
     * @return mixed
     * @author  Bobs
     */
    public function getRootDefaultPage()
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_order_bobs_parameters`  WHERE `parameters_id`=0");
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

    /**
     * Return name seo_page (page list)
     * @param $page_id
     * @return mixed
     * @author  Bobs
     */
    private function getNamePageByPageId($page_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'page_order_bobs_id=" . (int)$page_id . "'";
        $obj_sql = $this->db->query($sql);
        return $obj_sql->row['keyword'];
    }


    /**
     * Return count line table
     *
     * @return mixed count line table
     * @author  Bobs
     */
    public function getOrderPageCount()
    {
        $query = $this->db->query("SELECT COUNT(*) FROM `" . DB_PREFIX . "page_order_bobs`");
        return $query->row['COUNT(*)'];
    }

    /**
     * Return Page payment sort and limit (page list)
     * $data = array(
     *  'sort' => 'opd.page_id',
     *  'order' => 'ASC',
     *  'start' => 0,
     *  'limit' => 20 //$this->config->get('config_admin_limit')
     *  );
     * @param array $data
     * @return mixed
     * @author  Bobs
     */
    public function getPagesOrder($data = array())
    {
        $sql = "SELECT * FROM `" .
            DB_PREFIX . "page_order_bobs` op
                LEFT JOIN `" .
            DB_PREFIX . "page_order_bobs_description` opd
                ON
                (op.page_id = opd.page_id)";

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
        $pages = Array();
        foreach ($query->rows as $key => $page) {
            $pages[$key] = $page;
            $pages[$key]['keyword'] = $this->getNamePageByPageId($page['page_id']);
        }
        return $pages;
    }


    /**
     * Delete page payment (page list)
     * @param $page_id
     * @author  Bobs
     */
    public function deletePage($page_id)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "page_order_bobs` WHERE page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "page_order_bobs_description` WHERE
         page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "page_order_bobs_links` WHERE page_id = '" . (int)$page_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE
        query = 'page_order_bobs_id=" . (int)$page_id . "'");
    }


    public function savePage($array_page)
    {

        if (!$this->setPage($array_page)) {
            return false;
        }

        if (!$this->setPageDescription($array_page)) {
            return false;
        }

        if (!$this->setPageLink($array_page)) {
            return false;
        }

        if (!$this->setUrlAlias($array_page)) {
            return false;
        }
        return true;
    }

    private function setPage($array_page)
    {
        if (isset($array_page['page_id'])) {
            $page_id = $array_page['page_id'];
        } else {
            $page_id = 'null';
        }
        $sql = "REPLACE INTO `" . DB_PREFIX . "page_order_bobs` SET " .
            "`page_id` = " . $page_id . ", " .
            "`bottom` = " . (int)0 . ", " .
            "`status` = " . (int)1 . ", " .
            "`language_id` = " . (int)1 . ", " .
            "`store_id` = '" . (int)1 . "', " .
            "`one_price_total` = '" . $this->db->escape($array_page['one_price_total']) . "', " .
            "`one_percent` = " . (int)$array_page['one_percent'] . ", " .
            "`several_percent_default` = " . (int)$array_page['several_percent_default'] . ", " .
            "`several_percent` = '" . $this->db->escape($array_page['several_percent']) . "'";
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    private function setPageDescription($array_page)
    {

        if (isset($array_page['page_id'])) {
            $page_id = $array_page['page_id'];
        } else {
            $page_id = 'null';
        }
        $sql = "REPLACE INTO `" . DB_PREFIX . "page_order_bobs_description` SET " .
            "`page_id` = " . $page_id . ", " .
            "`order_id` = " . (int)$array_page['order_id'] . ", " .
            "`order_site_check` = " . (int)$array_page['order_site_check'] . ", " .
            "`order_site_id` = " . (int)$array_page['order_site_id'] . ", " .
            "`currency_code` = '" . $this->db->escape($array_page['currency_code']) . "', " .
            "`currency_code_check` = " . (int)$array_page['currency_code_check'] . ", " .
            "`type_of_presentation` = " . (int)$array_page['type_of_presentation'] . ", " .
            "`price` = '" . $this->db->escape($array_page['price']) . "', " .
            "`receiver_of_product` = '" . $this->db->escape($array_page['receiver_of_product']) . "', " .
            "`description_order` = '" . $this->db->escape($array_page['description_order']) . "', " .
            "`delivery_address` = '" . $this->db->escape($array_page['delivery_address']) . "', " .
            "`delivery_method` = '" . $this->db->escape($array_page['delivery_method']) . "', " .
            "`notes` = '" . $this->db->escape($array_page['notes']) . "', " .
            "`pay2pay_check` = '" . (int)$array_page['pay2pay_check'] . "', " .
            "`pay2pay_identifier_shop` = '" . $this->db->escape($array_page['pay2pay_identifier_shop']) . "', " .
            "`pay2pay_key_secret` = '" . $this->db->escape($array_page['pay2pay_key_secret']) . "', " .
            "`pay2pay_test_mode` = '" . (int)$array_page['pay2pay_test_mode'] . "', " .
            "`robokassa_check` = '" . (int)$array_page['robokassa_check'] . "', " .
            "`robokassa_identifier_shop` = '" . $this->db->escape($array_page['robokassa_identifier_shop']) . "', " .
            "`robokassa_key_secret` = '" . $this->db->escape($array_page['robokassa_key_secret']) . "', " .
            "`robokassa_test_mode` = " . (int)$array_page['robokassa_test_mode'] . ", " .
            "`interkassa_check` = '" . (int)$array_page['interkassa_check'] . "', " .
            "`interkassa_identifier_shop` = '" . $this->db->escape($array_page['interkassa_identifier_shop']) . "', " .
            "`interkassa_test_mode` = " . (int)$array_page['interkassa_test_mode'] . ", " .
            "`alter_payment_check` = " . (int)$array_page['alter_payment_check'] . ", " .
            "`alter_payment_text` = '" . $this->db->escape($array_page['alter_payment_text']) . "'";
        try {
            $this->db->query($sql);

        } catch (Exception $e) {
            return false;
        }

        return true;
    }


    private function setPageLink($array_page)
    {
        if (isset($array_page['page_id'])) {
            $page_id = $array_page['page_id'];
        } else {
            $page_id = 'null';
        }
        $this->db->query("DELETE FROM `" . DB_PREFIX . "page_order_bobs_links` WHERE page_id = '" . (int)$page_id . "'");
        if (!empty($array_page['links'])) {
            foreach ($array_page['links'] as $key => $links) {
                $sql = "INSERT INTO `" . DB_PREFIX . "page_order_bobs_links` SET
                `link_id`=NULL, " .
                    "`page_id` = " . (int)$page_id . ", " .
                    "`percent` = " . (int)$links['percent'] . ", " .
                    "`default` = " . (int)$links['default'] . ", " .
                    "`type` = '" . $this->db->escape($links['type']) . "', " .
                    "`link` = '" . $this->db->escape($links['link']) . "'";
                try {
                    $this->db->query($sql);

                } catch (Exception $e) {
                    return false;
                }
            }
        }
        return true;
    }


    private function setUrlAlias($array_page)
    {

        if (isset($array_page['page_id'])) {
            $sql = "UPDATE `" . DB_PREFIX . "url_alias`
                SET `keyword` = '" . $this->db->escape($array_page['name_page']) . "'
                WHERE `query` = 'page_order_bobs_id=" . (int)$array_page['page_id'] . "'";
        } else {
            $max_id = $this->db->query("SELECT MAX(`page_id`) FROM `" . DB_PREFIX . "page_order_bobs`");
            $max_id = $max_id->row['MAX(`page_id`)'];
            if ($max_id == null) {
                $max_id = 0;
            }
            $page_id = (int)$max_id + 1; //following line
            $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` (`url_alias_id`, `query`, `keyword`) VALUES (
            NULL, 'page_order_bobs_id=" . (int)$page_id . "', '" .
                $this->db->escape($array_page['name_page']) . "'
            )";
        }
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            return false;
        }


    }
}


?>