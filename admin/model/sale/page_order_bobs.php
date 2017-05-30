<?php

class ModelSalePageOrderBobs extends Model
{

    /**
     * Save parameters page
     *
     * @param array $array_page
     * @author  Bobs
     */
    public function setParameters($array_page, $save_page = false)
    {
        if ($save_page) {
            $array_page['get_order_id'] = "";
        }
        $sql = "REPLACE INTO `" . DB_PREFIX . "page_order_bobs_parameters` SET " .
            "`parameters_id` = 1, " .
            "`get_order_id` = " . (int)$array_page['get_order_id'] . ", " .
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
            "`alter_payment_text` = '" . $this->db->escape($array_page['alter_payment_text']) . "', " .
            "`one_price_total` = '" . $this->db->escape($array_page['one_price_total']) . "', " .
            "`one_percent` = " . (int)$array_page['one_percent'] . ", " .
            "`several_percent_default` = '" . $this->db->escape($array_page['several_percent_default']) . "', " .
            "`several_percent` = '" . $this->db->escape($array_page['several_percent']) . "'";
        $this->db->query($sql);
    }


    /**
     * Return parameters erge defalt and new
     *
     * @return array
     * @author  Bobs
     */
    public function getParameters()
    {
        $parameters = array_merge($this->getParametersNewValidate(), $this->getParametersDefaultValidate());
        return $parameters;
    }


    /**
     * Return new parameters, old - if now empty
     *
     * @return mixed
     * @author  Bobs
     */
    private function getParametersNewValidate()
    {
        $sql = "SELECT
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
            `alter_payment_text` FROM
            `" . DB_PREFIX . "page_order_bobs_parameters`
            WHERE
            `parameters_id`=IF(`parameters_id`=1,1,0)";

        $query = $this->db->query($sql);
        return $query->row;
    }


    /**
     * Return old (default) parameters
     *
     * @return mixed
     * @author  Bobs
     */
    private function getParametersDefaultValidate()
    {
        $sql = "SELECT
            `get_order_id`,
            `order_id`,
            `order_site_check`,
            `order_site_id`,
            `currency_code`,
            `currency_code_check`,
            `type_of_presentation` ,
            `price` ,
            `receiver_of_product`,
            `description_order`,
            `delivery_address`,
            `delivery_method`,
            `notes`,
            `one_price_total`,
            `one_percent`,
            `several_percent_default`,
            `several_percent` FROM
            `" . DB_PREFIX . "page_order_bobs_parameters`
            WHERE
            `parameters_id`=0";

        $query = $this->db->query($sql);
        return $query->row;
    }


    /**
     * Return is there string UrlAlias with Name and Page
     *
     * @param string $name_page
     * @param int    $page_id
     * @return bool
     * @author  Bobs
     */
    public function emptyUrlAliasNameAndId($name_page, $page_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias`
        WHERE `keyword`= '" . $this->db->escape($name_page) . "'
        AND `query`='page_order_bobs_id=" . (int)$page_id . "'";
        $obj_sql = $this->db->query($sql);
        if ($obj_sql->num_rows == 0) {
            return true;
        } else {
            return false;

        }
    }


    /**
     * Return is there string UrlAlias with Name
     *
     * @param string $name_page
     * @return bool
     * @author  Bobs
     */
    public function emptyUrlAliasName($name_page)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `keyword` LIKE '" . $this->db->escape($name_page) . "'";
        $obj_sql = $this->db->query($sql);
        if ($obj_sql->num_rows == 0) {
            return true;
        } else {
            return false;

        }
    }


    /**
     * Return Name Page page by order
     *
     * @param int $order_id
     * @return mixed
     * @author  Bobs
     */
    public function getNamePageByOrder($order_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "page_order_bobs_description` WHERE `order_id` LIKE " . (int)$order_id;
        $obj_sql_page = $this->db->query($sql);
        if ($obj_sql_page->num_rows == 0) {
            return false;
        } else {
            $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE
                    `query` LIKE 'page_order_bobs_id=" . (int)$obj_sql_page->row['page_id'] . "'";
            $obj_sql = $this->db->query($sql);
            return $obj_sql->row['keyword'];
        }
    }


    /**
     * Return Page page by page
     *
     * @param int $page_id
     * @return mixed
     * @author  Bobs
     */
    public function getPageByPage($page_id)
    {
        $sql = "SELECT * FROM
                `" . DB_PREFIX . "page_order_bobs` pob LEFT JOIN `" . DB_PREFIX . "page_order_bobs_description` pobd
                ON pob.page_id=pobd.page_id WHERE pob.page_id = " . (int)$page_id;
        $obj_sql_page = $this->db->query($sql);
        if ($obj_sql_page->num_rows == 0) {
            return false;
        }
        $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'page_order_bobs_id=" . (int)$page_id . "'";
        $obj_sql = $this->db->query($sql);
        $obj_sql_page->row['name_page'] = $obj_sql->row['keyword'];
        return $obj_sql_page->row;
    }


    /**
     * Return count line table
     *
     * @return int count line table
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
     *
     * @param array $data
     * @return array
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
     * Return name seo_page (page list)
     *
     * @param int $page_id
     * @return string
     * @author  Bobs
     */
    private function getNamePageByPageId($page_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE 'page_order_bobs_id=" . (int)$page_id . "'";
        $obj_sql = $this->db->query($sql);
        return $obj_sql->row['keyword'];
    }


    /**
     * Delete page payment (page list)
     *
     * @param int $page_id
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


    /**
     * Save Page
     *
     * @param array $array_page
     * @return bool
     * @author  Bobs
     */
    public function savePage($array_page)
    {

        $page_id = $this->getPageId($array_page);

        if (!$this->setPage($array_page, $page_id)) {
            return false;
        }

        if (!$this->setPageDescription($array_page, $page_id)) {
            return false;
        }

        if (!$this->setPageLink($array_page, $page_id)) {
            return false;
        }

        if (!$this->setPageUrlAlias($array_page, $page_id)) {
            return false;
        }
        return true;
    }


    /**
     * Return page_id (for function savePage)
     *
     * @param array $array_page
     * @return int
     * @author  Bobs
     */
    private function getPageId(array &$array_page)
    {
        if (isset($array_page['page_id'])) {
            $page_id = $array_page['page_id'];
        } else {
            $max_id = $this->db->query("SELECT MAX(`page_id`) FROM `" . DB_PREFIX . "page_order_bobs`");
            $max_id = $max_id->row['MAX(`page_id`)'];
            if ($max_id == null) {
                $max_id = 0;
            }
            $page_id = (int)$max_id + 1; //following line
        }
        return $page_id;
    }


    /**
     * Set table page_order_bobs (for function savePage)
     *
     * @param array $array_page
     * @param int $page_id
     * @return bool
     * @author  Bobs
     */
    private function setPage($array_page, $page_id)
    {
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


    /**
     * Set table page_order_bobs_description (for function savePage)
     *
     * @param array $array_page
     * @param int $page_id
     * @return bool
     * @author  Bobs
     */
    private function setPageDescription($array_page, $page_id)
    {
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


    /**
     * Set table page_order_bobs_links (for function savePage)
     *
     * @param array $array_page
     * @param int $page_id
     * @return bool
     * @author  Bobs
     */
    private function setPageLink($array_page, $page_id)
    {
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


    /**
     * Set table url_alias (for function savePage)
     *
     * @param array $array_page
     * @param int $page_id
     * @return bool
     * @author  Bobs
     */
    private function setPageUrlAlias($array_page, $page_id)
    {
        $sql = "SELECT `url_alias_id` FROM `" . DB_PREFIX . "url_alias`
                WHERE
                `query` = 'page_order_bobs_id=" . (int)$page_id . "'";
        $url_alias_id = $this->db->query($sql);
        if ($url_alias_id->num_rows != 0) {
            $url_alias_id = $url_alias_id->row['url_alias_id']; //following line
        } else {
            $max_url_alias_id = $this->db->query("SELECT MAX(`url_alias_id`) FROM `" . DB_PREFIX . "url_alias`");
            $max_url_alias_id = $max_url_alias_id->row['MAX(`url_alias_id`)'];
            $url_alias_id = (int)$max_url_alias_id + 1; //following line
        }

        $sql = "REPLACE `" . DB_PREFIX . "url_alias`
                SET
                `url_alias_id` = " . (int)$url_alias_id . ",
                `query` = 'page_order_bobs_id=" . (int)$page_id . "',
                `keyword` = '" . $this->db->escape($array_page['name_page']) . "'";
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}


?>