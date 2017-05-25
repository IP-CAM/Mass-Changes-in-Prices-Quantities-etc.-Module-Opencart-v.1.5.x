<?php

class ControllerModulePageOrderBobs extends Controller
{

    public function index()
    {

        $this->load->language('module/page_order_bobs');
        $this->document->setTitle($this->language->get('heading_title'));


        //SET UP BREADCRUMB TRAIL. YOU WILL NOT NEED TO MODIFY THIS UNLESS YOU CHANGE YOUR MODULE NAME.
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/page_order_bobs', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );


        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['author_label'] = $this->language->get('author_label');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['button_cancel_label'] = $this->language->get('button_cancel_label');


        //Choose which template file will be used to display this request.
        $this->template = 'module/page_order_bobs.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        //Send the output.
        $this->response->setOutput($this->render());
    }


    public function install()
    {
        $this->load->language('module/page_order_bobs');
        $sql = "CREATE TABLE IF NOT EXISTS  `" . DB_PREFIX . "page_order_bobs`
      ( `page_id` INT(11) NOT NULL AUTO_INCREMENT,
      `bottom`INT(11) NOT NULL ,
      `status` INT(11) NOT NULL ,
      `store_id` INT(11) NOT NULL ,
      PRIMARY KEY(`page_id`))
       ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8";
        $this->db->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS  `" . DB_PREFIX . "page_order_bobs_description`
      ( `page_id` INT(11) NOT NULL AUTO_INCREMENT ,
      `order_id` INT(11) NOT NULL ,
      `order_alter_check` TINYINT(1) NOT NULL ,
      `order_alter_id` INT(11) NOT NULL ,
      `language_id` INT(11) NOT NULL ,
      `currency_code` VARCHAR(3) NOT NULL ,
      `currency_code_check` TINYINT(1) NOT NULL ,
      `price_total` DECIMAL(15,4) NOT NULL ,
      `price` DECIMAL(15,4) NOT NULL ,
      `option_client_percent_default` INT(11) NOT NULL ,
      `option_client_percent` TEXT NOT NULL ,
      `per_cent_of_all` INT(11) NOT NULL ,
      `receiver_of_product` VARCHAR(63) NOT NULL ,
      `description_order` TEXT NOT NULL ,
      `delivery_address` VARCHAR(255) NOT NULL ,
      `delivery_method` VARCHAR(255) NOT NULL ,
      `notes` TEXT NOT NULL ,
      `pay2pay_check` TINYINT(1) NOT NULL ,
      `pay2pay_identifier_shop` VARCHAR(63) NOT NULL ,
      `pay2pay_key_secret` VARCHAR(63) NOT NULL ,
      `pay2pay_test_mode` TINYINT(1) NOT NULL ,
      `robokassa_check` TINYINT(1) NOT NULL ,
      `robokassa_identifier_shop` VARCHAR(63) NOT NULL ,
      `robokassa_key_secret` VARCHAR(63) NOT NULL ,
      `robokassa_test_mode` TINYINT(1) NOT NULL ,
      `interkassa_check` TINYINT(1) NOT NULL ,
      `interkassa_identifier_shop` VARCHAR(63) NOT NULL ,
      `interkassa_test_mode` TINYINT(1) NOT NULL ,
      `alter_payment_check` TINYINT(1) NOT NULL ,
      `alter_payment_text` TEXT NOT NULL ,
      PRIMARY KEY (`page_id`))
      ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8";
        $this->db->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS  `" . DB_PREFIX . "page_order_bobs_links`
      ( `link_id` INT(11) AUTO_INCREMENT NOT NULL ,
      `page_id` INT(11) NOT NULL ,
      `percent` INT(11) NOT NULL ,
      `default` TINYINT(1) NOT NULL,
      `type` VARCHAR(255) NOT NULL ,
      `link` TEXT NOT NULL,
      PRIMARY KEY(`link_id`))
       ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8";
        $this->db->query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS  `" . DB_PREFIX . "page_order_bobs_parameters`
    ( `parameters_id` INT(11) NOT NULL ,
    `get_order_id` INT(11) NULL ,
    `currency_code_check` TINYINT(1) NOT NULL ,
    `option_client_percent_default` INT(11) NOT NULL ,
    `option_client_percent` TEXT NOT NULL ,
    `pay2pay_check` TINYINT(1) NOT NULL ,
    `pay2pay_identifier_shop` VARCHAR(63) NOT NULL ,
    `pay2pay_key_secret` VARCHAR(63) NOT NULL ,
    `pay2pay_test_mode` TINYINT(1) NOT NULL ,
    `robokassa_check` TINYINT(1) NOT NULL ,
    `robokassa_identifier_shop` VARCHAR(63) NOT NULL ,
    `robokassa_key_secret` VARCHAR(63) NOT NULL ,
    `robokassa_test_mode` TINYINT(1) NOT NULL ,
    `interkassa_check` TINYINT(1) NOT NULL ,
    `interkassa_identifier_shop` VARCHAR(63) NOT NULL ,
    `interkassa_test_mode` TINYINT(1) NOT NULL ,
    `alter_payment_check` TINYINT(1) NOT NULL ,
    `alter_payment_text` TEXT NOT NULL)
     ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8";
        $this->db->query($sql);


        $sql = "REPLACE INTO  `" . DB_PREFIX . "page_order_bobs_parameters` SET
            `parameters_id` = '1',
            `currency_code_check` =0 ,
            `get_order_id` = null,
            `option_client_percent_default` = 100 ,
            `option_client_percent` = '' ,
            `pay2pay_check` = 1,
            `pay2pay_identifier_shop` = '57249',
            `pay2pay_key_secret` = 'nordston',
            `pay2pay_test_mode` = 1,
            `robokassa_check` = 1,
            `robokassa_identifier_shop` = 'nordstonru',
            `robokassa_key_secret` = 'j3lOoJJUBy3h22T5zxhu',
            `robokassa_test_mode` = 1,
            `interkassa_check` = 1,
            `interkassa_identifier_shop` = '54e32cdc76a3247a198b4567',
            `interkassa_test_mode` = 1,
            `alter_payment_check` = 1,
            `alter_payment_text` = 'Оплатить с помощью прямого перевода на карту СберБанка 0000-0000-0000-0000 Андрей С.'";
        //$this->data['success'] = $this->language->get('success_installation');
        $this->db->query($sql); //Create datbase line
        $this->load->model('setting/setting');
        $msettings = array('page_order_bobs'=>array('update_quantity'=>1,'update_options'=>1,'page_order_bobs_version'=>'1.0'));
        $this->model_setting_setting->editSetting('page_order_bobs', $msettings);
        $this->session->data['success'] = $this->language->get('success_installation');

    }

    public function uninstall()
    {

        $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query LIKE 'page_order_bobs_id%'");
        $this->db->query("DROP TABLE IF EXISTS
			`" . DB_PREFIX . "page_order_bobs`,
			`" . DB_PREFIX . "page_order_bobs_description`,
			`" . DB_PREFIX . "page_order_bobs_parameters`,
			`" . DB_PREFIX . "page_order_bobs_links`");

    }





}

?>