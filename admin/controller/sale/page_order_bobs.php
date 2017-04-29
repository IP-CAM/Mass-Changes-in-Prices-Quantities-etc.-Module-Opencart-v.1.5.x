<?php

class ControllerSalePageOrderBobs extends Controller
{
    private $name_page_seo = 'oplata-zakaz-%s';

    public function post()
    {

        $this->language->load('sale/page_order_bobs');
        $json = array();
        if (!isset($this->request->post['price']) || !isset($this->request->post['percent']) ||
            !isset($this->request->post['description_order'])
        ) {
            exit;
        }
        $percent = substr($this->request->post['percent'], 0, -1);
        $price = $this->request->post['price'] * $percent / 100;
        $json['price'] = floor($price); //delete  TODO
        $prefix_damp = $this->currency->format(1000);
        $prefix = mb_substr($prefix_damp, -2, 2, 'UTF-8'); //p.
        $pattern = '/' . $this->language->get('per_cent_of_all_description_text') . '.*%, ' . mb_strtolower($this->language->get('price_new_label'), 'UTF-8') . '.*' . $prefix . '/';
        $text = $this->request->post['description_order'];
        if (preg_match($pattern, $text)) { //text empty (yes)
            if ($percent == '100') { //delete text description_order
                $text = preg_replace($pattern, '', $text); //DELETE
                $pattern = '/\n$/';
                $json['description_order'] = preg_replace($pattern, '', $text);
            } else {
                $patterns = Array();
                $patterns[0] = '/' . $this->language->get('per_cent_of_all_description_text') . '.*%/';
                $patterns[1] = '/' . mb_strtolower($this->language->get('price_new_label'), 'UTF-8') . '.*' . $prefix . '/';
                $replace = array();
                $replace[0] = $this->language->get('per_cent_of_all_description_text') . ' ' . $this->request->post['percent'];
                $replace[1] = mb_strtolower($this->language->get('price_new_label'), 'UTF-8') . ' ' . $this->currency->format(floor($price)); //delete  TODO
                $json['description_order'] = preg_replace($patterns, $replace, $text);
            }
        } else {
            if ($percent == '100') {
                $json['description_order'] = $this->request->post['description_order'];
            } else {
                $json['description_order'] = $this->request->post['description_order'] . "\n" . $this->language->get('per_cent_of_all_description_text') . ' ' . $this->request->post['percent'] . ', ' . mb_strtolower($this->language->get('price_new_label'), 'UTF-8') . ' ' . $this->currency->format($price);
            }
        }
        $json['price_total_text'] = $this->language->get('price_total_text') . ' ' . $this->currency->format($this->request->post['price']);
        $this->response->setOutput(json_encode($json));

    }

    public function index()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $this->getList();
    }

    public function update()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $page = $this->model_sale_page_order_bobs->getPageByPage($this->request->get['page_id']);
        $this->getForm($page);
    }

    public function insert()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $this->getForm();
    }

    public function link()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $page_form = false;
        $this->getForm(null, $page_form);
    }

    public function delete()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $page_id) {
                $this->model_sale_page_order_bobs->deleteOrderPage($page_id);
            }

            $this->session->data['success'] = $this->language->get('page_delete_label');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }


    public function terminal()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $this->load->model('sale/order');

        if (empty($this->request->post)) {
            $this->getForm();
            return;
        }
        if ($this->validateForm()) {
            if ($this->request->post['terminal_id'] == 1) { //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
                $this->getOrderId(1);
            } elseif ($this->request->post['terminal_id'] == 3) {
                $this->getOrderId(3);
            } elseif ($this->request->post['terminal_id'] == 0) { //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
                $array_post_parameter = $this->modifierPostToArray($this->request->post); //Создаем массив

                if ($this->addAndUpdatePage($array_post_parameter)) {
                    $array_post_parameter['get_order_id'] = null;
                    $this->model_sale_page_order_bobs->setParameters($array_post_parameter);//Save parameters
                    $this->cache->delete('seo_pro'); // clear cache seo
                    $url = '';

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                    }

                    $this->redirect($this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                } else {

                    $array_post_parameter = $this->modifierPostToArray($this->request->post); //Создаем массив
                    $this->model_sale_page_order_bobs->setParameters($array_post_parameter);//Save parameters
                    $this->getForm($array_post_parameter);
                }

            } elseif ($this->request->post['terminal_id'] == 2) //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
            {
                $array_post_parameter = $this->modifierPostToArray($this->request->post); //Создаем массив
                $this->model_sale_page_order_bobs->setParameters($array_post_parameter);//Save parameters
                $array_link = $this->getLink();
                $array_post_parameter = array_merge($array_post_parameter, $array_link);
                $this->getForm($array_post_parameter, false);
            }
            //errors:
        } else {
            if ($this->request->post['terminal_id'] == 0 || $this->request->post['terminal_id'] == 1) {
                $array_post_parameter = $this->modifierPostToArray($this->request->post); //Создаем массив
                $this->model_sale_page_order_bobs->setParameters($array_post_parameter);//Save parameters
                $this->getForm($array_post_parameter);
            } elseif ($this->request->post['terminal_id'] == 2 || $this->request->post['terminal_id'] == 3) {
                $array_post_parameter = $this->modifierPostToArray($this->request->post); //Создаем массив
                $this->model_sale_page_order_bobs->setParameters($array_post_parameter);//Save parameters
                $this->getForm($array_post_parameter, false);
            }

        }

    }


    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'opd.page_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $this->data['link_form'] = $this->url->link('sale/page_order_bobs/link', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['insert'] = $this->url->link('sale/page_order_bobs/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('sale/page_order_bobs/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['pages'] = array();

        $data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $page_order_total = $this->model_sale_page_order_bobs->getTotalOrderPageCount();

        $results = $this->model_sale_page_order_bobs->getPagesOrder($data);

        $server_host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('sale/page_order_bobs/update', 'token=' . $this->session->data['token'] . '&page_id=' . $result['page_id'] . $url, 'SSL')
            );

            if ($result['price'] == $result['price_total']) {
                $price = $this->currency->format($result['price_total']);
            } else {
                $price = $this->currency->format($result['price_total']) . $this->language->get('price_list') . $result['per_cent_of_all'] . '%';
            }

            if ($this->config->get('config_seo_url')) {
                $link = $server_host . $result['keyword']; //$this->model_module_page_order_bobs->,
            } else {
                $link = $server_host . 'index.php?route=information/page_order_bobs&page_order_bobs_id=' . $result['page_id'];
            }

            $this->data['pages'][] = array(
                'page_id' => $result['page_id'],
                'order_id' => $result['order_id'],
                'column_link_page' => $link,
                'receiver_of_product' => $result['receiver_of_product'],
                'price' => $price,
                'selected' => isset($this->request->post['selected']) && in_array($result['information_id'], $this->request->post['selected']),
                'action' => $action
            );
        }


        //language
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['column_page_id_label'] = $this->language->get('column_page_id_label');
        $this->data['column_order_id_label'] = $this->language->get('column_order_id_label');
        $this->data['column_link_page_label'] = $this->language->get('column_link_page_label');
        $this->data['column_receiver_of_product_label'] = $this->language->get('column_receiver_of_product_label');
        $this->data['column_price_label'] = $this->language->get('column_price_label');
        $this->data['column_action_label'] = $this->language->get('column_action_label');

        $this->data['text_no_results_label'] = $this->language->get('text_no_results_label');

        $this->data['button_link_form_label'] = $this->language->get('button_link_form_label');
        $this->data['button_insert_label'] = $this->language->get('button_insert_label');
        $this->data['button_delete_label'] = $this->language->get('button_delete_label');


        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sort_page_id'] = $this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . '&sort=opd.page_id' . $url, 'SSL');
        $this->data['sort_sort_order_id'] = $this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . '&sort=opd.order_id' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        //Показано с 1 по 4 из 4 (всего страниц: 1)
        $pagination = new Pagination();
        $pagination->total = $page_order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'sale/page_order_bobs_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }


    protected function getForm($array_post_parameter = null, $page_form = true)
    {

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['page_form'] = (int)$page_form;


        //Названия полей и значения по умолчанию

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['heading_title_link'] = $this->language->get('heading_title_link');

        //default
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        //default
        $this->data['button_get_link_label'] = $this->language->get('button_get_link_label');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');

        $this->data['get_order_id_label'] = $this->language->get('get_order_id_label');
        $this->data['get_order_id_button_label'] = $this->language->get('get_order_id_button_label');


        $this->data['order_id_label'] = $this->language->get('order_id_label');
        $this->data['order_alter_check_label'] = $this->language->get('order_alter_check_label');
        $this->data['order_alter_id_label'] = $this->language->get('order_alter_id_label');
        $this->data['currency_code_check_label'] = $this->language->get('currency_code_check_label');
        $this->data['currency_code_label'] = $this->language->get('currency_code');
        $this->data['price_label'] = $this->language->get('price_label');
        $this->data['price_new_label'] = $this->language->get('price_new_label');
        $this->data['option_client_percent_label'] = $this->language->get('option_client_percent_label');
        $this->data['option_client_percent_default_label'] = $this->language->get('option_client_percent_default_label');
        $this->data['option_client_expand_label'] = $this->language->get('option_client_expand_label');
        $this->data['option_client_down_label'] = $this->language->get('option_client_down_label');

        $this->data['receiver_of_product_label'] = $this->language->get('receiver_of_product');
        $this->data['description_order_label'] = $this->language->get('description_order');
        $this->data['delivery_address_label'] = $this->language->get('delivery_address');
        $this->data['delivery_method_label'] = $this->language->get('delivery_method');


        $this->data['price_modif_alert'] = $this->language->get('price_modif_alert');
        $this->data['notes_label'] = $this->language->get('notes');

        //Pay2pay
        $this->data['name_payment_pay2pay_label'] = $this->language->get('name_payment_pay2pay');
        $this->data['identifier_shop_pay2pay_label'] = $this->language->get('identifier_shop_pay2pay');
        $this->data['key_secret_pay2pay_label'] = $this->language->get('key_secret_pay2pay');
        $this->data['test_mode_pay2pay_label'] = $this->language->get('test_mode_pay2pay_label');
        //Robokassa
        $this->data['name_payment_robokassa_label'] = $this->language->get('name_payment_robokassa');
        $this->data['identifier_shop_robokassa_label'] = $this->language->get('identifier_shop_robokassa');
        $this->data['key_secret_robokassa_label'] = $this->language->get('key_secret_robokassa');
        $this->data['test_mode_robokassa_label'] = $this->language->get('test_mode_robokassa_label');
        //interkassa
        $this->data['name_payment_interkassa_label'] = $this->language->get('name_payment_interkassa');
        $this->data['identifier_shop_interkassa_label'] = $this->language->get('identifier_shop_interkassa');
        $this->data['test_mode_interkassa_label'] = $this->language->get('test_mode_interkassa_label');

        //alter payment
        $this->data['alter_payment_label'] = $this->language->get('alter_payment_label');
        $this->data['alter_payment_text_label'] = $this->language->get('alter_payment_text_label');

        $this->data['create_a_page_label'] = $this->language->get('create_a_page');
        $this->data['change_name_page_label'] = $this->language->get('change_name_page_label');
        $this->data['name_page_label'] = $this->language->get('name_page');
        $this->data['page_host_label'] = $this->language->get('page_host');

        $this->data['link_pay2pay_label'] = $this->language->get('link_pay2pay_label');
        $this->data['link_robokassa_label'] = $this->language->get('link_robokassa_label');
        $this->data['link_interkassa_label'] = $this->language->get('link_interkassa_label');


        $this->data['page_host'] = 'http://' . $_SERVER['HTTP_HOST'] . '/'; //Name site


        $page_order_parameters = $this->model_sale_page_order_bobs->getParameters();//Get parameters
        if ($page_order_parameters['get_order_id'] === null) {
            $page_order_parameters['get_order_id'] = '';
        }
        $this->data['name_page_seo'] = $this->name_page_seo;
        if (sizeof($array_post_parameter)) {
            $this->data['get_order_id'] = $page_order_parameters['get_order_id'];
            $this->data['order_id'] = $array_post_parameter['order_id'];
            $this->data['language_id'] = $array_post_parameter['language_id'];
            $this->data['currency_code'] = $array_post_parameter['currency_code'];
            $this->data['currency_code_check'] = $array_post_parameter['currency_code_check'];
            $this->data['price_total'] = $array_post_parameter['price_total'];
            $this->data['price'] = $array_post_parameter['price'];
            $this->data['per_cent_of_all'] = $array_post_parameter['per_cent_of_all'];
            if ($array_post_parameter['price_total'] != $array_post_parameter['price']) {
                $this->data['price_total_text'] = $this->language->get('price_total_text') . ' ' . $this->currency->format($array_post_parameter['price_total']);
            }
            if ($page_form) {
                $this->data['option_client_percent_default'] =
                    ($array_post_parameter['option_client_percent_default'] != null) ?
                        $array_post_parameter['option_client_percent_default'] : 10;
                $this->data['option_client_percent'] =
                    ($array_post_parameter['option_client_percent'] != null) ?
                        unserialize($array_post_parameter['option_client_percent']) :
                        $array_post_parameter['option_client_percent'];
            } else {
                $this->data['option_client_percent_default'] =
                    ($page_order_parameters['option_client_percent_default'] != null) ?
                        $page_order_parameters['option_client_percent_default'] : 10;

                $this->data['option_client_percent'] =
                    ($page_order_parameters['option_client_percent'] != null) ?
                        unserialize($page_order_parameters['option_client_percent']) :
                        $page_order_parameters['option_client_percent'];
            }
            $this->data['receiver_of_product'] = $array_post_parameter['receiver_of_product'];
            $this->data['description_order'] = $array_post_parameter['description_order'];
            $this->data['delivery_address'] = $array_post_parameter['delivery_address'];
            $this->data['delivery_method'] = $array_post_parameter['delivery_method'];
            $this->data['notes'] = $array_post_parameter['notes'];

            if (isset($array_post_parameter['notes_client'])) {
                $this->data['notes_client'] = $array_post_parameter['notes_client'];
            }

            $this->data['pay2pay_check'] = $array_post_parameter['pay2pay_check'];
            $this->data['pay2pay_identifier_shop'] = $array_post_parameter['pay2pay_identifier_shop'];
            $this->data['pay2pay_key_secret'] = $array_post_parameter['pay2pay_key_secret'];
            $this->data['pay2pay_test_mode'] = $array_post_parameter['pay2pay_test_mode'];

            $this->data['robokassa_check'] = $array_post_parameter['robokassa_check'];
            $this->data['robokassa_identifier_shop'] = $array_post_parameter['robokassa_identifier_shop'];
            $this->data['robokassa_key_secret'] = $array_post_parameter['robokassa_key_secret'];
            $this->data['robokassa_test_mode'] = $array_post_parameter['robokassa_test_mode'];

            $this->data['interkassa_check'] = $array_post_parameter['interkassa_check'];
            $this->data['interkassa_identifier_shop'] = $array_post_parameter['interkassa_identifier_shop'];
            $this->data['interkassa_test_mode'] = $array_post_parameter['interkassa_test_mode'];
            if ($page_form) {
                $this->data['alter_payment_check'] = $array_post_parameter['alter_payment_check'];
                $this->data['alter_payment_text'] = $array_post_parameter['alter_payment_text'];
            } else {
                $this->data['alter_payment_check'] = $page_order_parameters['alter_payment_check'];
                $this->data['alter_payment_text'] = $page_order_parameters['alter_payment_text'];
            }

            if ($page_form) {
                $this->data['name_page'] = $array_post_parameter['name_page'];
                $this->data['order_alter_check'] = $array_post_parameter['order_alter_check'];
                $this->data['order_alter_id'] = $array_post_parameter['order_alter_id'];
            } else {
                $this->data['link_pay2pay'] = $array_post_parameter['link_pay2pay'];
                $this->data['link_robokassa'] = $array_post_parameter['link_robokassa'];
                $this->data['link_interkassa'] = $array_post_parameter['link_interkassa'];
            }


        } else {

            $this->data['get_order_id'] = '';
            $this->data['order_id'] = "99";
            $this->data['order_alter_check'] = 1;
            $this->data['order_alter_id'] = '';
            $this->data['language_id'] = (int)$this->config->get('config_language_id');
            $this->data['currency_code'] = 'RUB';

            $this->data['price_total'] = '1000';
            $this->data['price'] = '1000';
            $this->data['per_cent_of_all'] = '100';

            $this->data['option_client_percent_default'] =
                ($page_order_parameters['option_client_percent_default'] != null) ?
                    $page_order_parameters['option_client_percent_default'] : 10;

            $this->data['option_client_percent'] =
                ($page_order_parameters['option_client_percent'] != null &&
                    $page_order_parameters['option_client_percent'] != '') ?
                    unserialize($page_order_parameters['option_client_percent']) : null;

            $this->data['receiver_of_product'] = 'Вася Пупкин';
            $this->data['description_order'] = 'описание заказа';
            $this->data['delivery_address'] = '';
            $this->data['delivery_method'] = '';
            $this->data['notes'] = '';

            $this->data['currency_code_check'] = $page_order_parameters['currency_code_check'];

            $this->data['pay2pay_check'] = $page_order_parameters['pay2pay_check'];
            $this->data['pay2pay_identifier_shop'] = $page_order_parameters['pay2pay_identifier_shop'];
            $this->data['pay2pay_key_secret'] = $page_order_parameters['pay2pay_key_secret'];
            $this->data['pay2pay_test_mode'] = $page_order_parameters['pay2pay_test_mode'];

            $this->data['robokassa_check'] = $page_order_parameters['robokassa_check'];
            $this->data['robokassa_identifier_shop'] = $page_order_parameters['robokassa_identifier_shop'];
            $this->data['robokassa_key_secret'] = $page_order_parameters['robokassa_key_secret'];
            $this->data['robokassa_test_mode'] = $page_order_parameters['robokassa_test_mode'];

            $this->data['interkassa_check'] = $page_order_parameters['interkassa_check'];
            $this->data['interkassa_identifier_shop'] = $page_order_parameters['interkassa_identifier_shop'];
            $this->data['interkassa_test_mode'] = $page_order_parameters['interkassa_test_mode'];

            $this->data['alter_payment_check'] = $page_order_parameters['alter_payment_check'];
            $this->data['alter_payment_text'] = $page_order_parameters['alter_payment_text'];

            $this->data['name_page'] = sprintf($this->name_page_seo, $this->data['order_id']);


            $this->data['link_pay2pay'] = $this->language->get('link_pay2pay');
            $this->data['link_robokassa'] = $this->language->get('link_robokassa');
            $this->data['link_interkassa'] = $this->language->get('link_interkassa');


        }


        //for undo
        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (!isset($this->request->get['page_id'])) {
            $this->data['action'] = $this->url->link('sale/page_order_bobs/terminal', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('sale/page_order_bobs/terminal', 'token=' . $this->session->data['token'] . '&page_id=' . $this->request->get['page_id'] . $url, 'SSL');
        }

        $this->data['cancel'] = $this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['post_link'] = 'index.php?route=sale/page_order_bobs/post&token=' . $this->session->data['token'];

        $this->template = 'sale/page_order_bobs_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function getOrderId($id_terminal) //( terminal_id ) !0 - save page, 1 - to make the data from the number order, !2 - create link, 3 - to make the data from the number order (form link)
    {


        $get_order_id = 0;
        if (isset($this->request->post['get_order_id'])) {
            $get_order_id = $this->request->post['get_order_id'];
        }

        $order = $this->model_sale_order->getOrder($get_order_id);


        $order_products = $this->model_sale_order->getOrderProducts($get_order_id);

        $array_post_parameter = $this->modifierPostToArray($this->request->post); //Создаем массив
        $array_post_parameter['order_id'] = $order['order_id'];
        $array_post_parameter['order_alter_id'] = $order['order_id'];
        $array_post_parameter['order_alter_check'] = 1;
        $array_post_parameter['name_page'] = sprintf($this->name_page_seo, $array_post_parameter['order_id']);
        $array_post_parameter['currency_code'] = $order['currency_code'];
        $array_post_parameter['price'] = $order['total'];
        $array_post_parameter['price_total'] = $order['total'];
        $array_post_parameter['per_cent_of_all'] = '100';

        if ($order['lastname'] != '') {
            if ($order['firstname'] != '') {
                $array_post_parameter['receiver_of_product'] = $order['lastname'] . ' ' . $order['firstname'];
            } else {
                $array_post_parameter['receiver_of_product'] = $order['lastname'];
            }
        } else {
            if ($order['firstname'] != '') {
                $array_post_parameter['receiver_of_product'] = $order['firstname'];
            } else {
                $array_post_parameter['receiver_of_product'] = '';
            }
        }

        $description_order = '';
        foreach ($order_products as $order_product) {
            $order_options = $this->model_sale_order->getOrderOptions($get_order_id, $order_product['order_product_id']);
            if (strpos($order_product['name'], '-') != false) {
                $name = substr($order_product['name'], 0, strpos($order_product['name'], '-'));
                $name = trim($name);
                $description_order = $description_order . $name;

            } else {
                $description_order = $description_order . $order_product['name'];
            }
            $description_order .= ': ';
            $description_order .= $this->language->get('quantity') . ' ' . $order_product['quantity'] . ', ';
            $description_order .= $this->language->get('price') . ' ' . $this->currency->format($order_product['price']) . ', ';
            $description_order .= $this->language->get('total') . ' ' . $this->currency->format($order_product['total']) . "\n\t";
            foreach ($order_options as $key => $order_option) {
                $description_order .= ' ' . $order_option['name'] . ': ' . $order_option['value'];
                if ($key < count($order_options) - 1) {
                    $description_order .= "\n\t";
                } else {
                    $description_order .= "\n";
                }
            }
        }
        $description_order .= $this->language->get('total_all') . ' ' . $this->currency->format($order['total']);
        $array_post_parameter['description_order'] = $description_order;

        $array_post_parameter['delivery_address'] = $order['shipping_address_1'];
        $array_post_parameter['delivery_method'] = '';

        if ($order['comment'] != '') {
            $array_post_parameter['notes_client'] = $this->language->get('notes_client_of_order') . ' ' . $order['comment'];
        }

        $this->model_sale_page_order_bobs->setParameters($array_post_parameter);//Save parameters, save get_page_id
        if ($id_terminal == 1) {
            $this->getForm($array_post_parameter);
        } elseif ($id_terminal == 3) {
            $array_post_parameter = $this->modifierLinkNull($array_post_parameter);
            $this->getForm($array_post_parameter, false);
        }


    }


    private function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'sale/page_order_bobs')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }


    private function addAndUpdatePage($array_post_parameter)
    {

        $array_link = $this->getLink();
        $array_post_parameter = array_merge($array_link, $array_post_parameter);
        if (isset($this->request->get['page_id'])) {
            if ($this->model_sale_page_order_bobs->updatePage($array_post_parameter, $this->request->get['page_id'])) {
                $this->session->data['success'] = $this->language->get('success_page_update');
                return true;
            } else {
                $this->data['errors_warning'][] = 'error BD save page';
                return false;
            }
        } else {
            if ($this->model_sale_page_order_bobs->addPage($array_post_parameter)) {
                $this->session->data['success'] = $this->language->get('success_page_insert');
                return true;
            } else {
                $this->data['errors_warning'][] = 'error BD save page';
                return false;
            }
        }

    }

    private function validateForm()
    {
        //Errors
        if ($this->request->post['terminal_id'] == 1 || $this->request->post['terminal_id'] == 3) { //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
            $get_order_id = $this->request->post['get_order_id'];
            if ($get_order_id == '') {
                $this->data['errors_warning'][] = $this->language->get('null_number_order');
                return false;
            }
            if (!$this->model_sale_order->getOrder($get_order_id)) {
                $this->data['errors_warning'][] = $this->language->get('no_number_order');
            }


        } elseif ($this->request->post['terminal_id'] == 0) {

            $order_id = $this->request->post['order_id']; // number of order
            if (preg_match('/[^0-9]/', $order_id) || $order_id == '') {
                $this->data['errors_warning'][] = $this->language->get('error_number_order');
            }

            $name_page = $this->request->post['name_page'];
            if (preg_match('/[^A-Za-zА-Яа-я0-9_\-]/', $name_page) || $name_page == '') {
                $this->data['errors_warning'][] = $this->language->get('error_name_page');
            }
            if (isset($this->request->get['page_id'])) { //Есть ли имя такое же, если есть, и она ссылается не на нашу страницу, запрещяем
                if ($this->model_sale_page_order_bobs->findUrlAliasName($this->request->post['name_page'])) {
                    if (!$this->model_sale_page_order_bobs->suitableUrlAliasNameAndId($this->request->post['name_page'], $this->request->get['page_id'])) {

                        $this->data['errors_warning'][] = 'error_name_page 3';
                        return false;
                    }
                }
            } else { //Есть ли имя страницы такое же есть, то запрещяем создание нового
                if ($this->model_sale_page_order_bobs->findUrlAliasName($this->request->post['name_page'])) {

                    $this->data['errors_warning'][] = $this->language->get('error_duplicate_page');
                    return false;
                }
            }

            $currency_code = $this->request->post['currency_code'];
            if (preg_match('/[^A-Za-z]/', $currency_code) || $currency_code == '') {
                $this->data['errors_warning'][] = $this->language->get('error_currency_code');
            }

            $price = $this->request->post['price'];
            if (preg_match('/[^0-9.]/', $price) || $price == '' || !is_numeric($price)) {
                $this->data['errors_warning'][] = $this->language->get('error_prince_order');
            }

            $receiver_of_product = $this->request->post['receiver_of_product'];
            if ($receiver_of_product == '') {
                $this->data['errors_warning'][] = $this->language->get('error_receiver_of_product');
            }


            if (isset($this->request->post['pay2pay_check'])) {
                $identifier_order = $this->request->post['pay2pay_identifier_shop'];
                $key_secret = $this->request->post['pay2pay_key_secret'];
                $test_mode = $this->request->post['pay2pay_test_mode'];
                if (empty($identifier_order) || ($test_mode != 0 && $test_mode != 1) || empty($key_secret)) {
                    $this->data['errors_warning'][] = 'incorrect data pay2pay';
                }
            }
            if (isset($this->request->post['robokassa_check'])) {
                $robokassa_identifier_shop = $this->request->post['robokassa_identifier_shop'];
                $robokassa_key_secret = $this->request->post['robokassa_key_secret'];
                if (empty($robokassa_identifier_shop) || empty($robokassa_key_secret)) {
                    $this->data['errors_warning'][] = 'incorrect data robocassa';
                }
            }
            if (isset($this->request->post['interkassa_check'])) //interkassa
            {
                $identifier_order = $this->request->post['interkassa_identifier_shop'];
                if ($identifier_order == '') {
                    $this->data['errors_warning'][] = 'incorrect data intercassa';
                }
            }

        } elseif ($this->request->post['terminal_id'] == 2) {
            $order_id = $this->request->post['order_id']; // number of order
            if (preg_match('/[^0-9]/', $order_id) || $order_id == '') {
                $this->data['errors_warning'][] = $this->language->get('error_number_order');
            }

            $currency_code = $this->request->post['currency_code'];
            if (preg_match('/[^A-Za-z]/', $currency_code) || $currency_code == '') {
                $this->data['errors_warning'][] = $this->language->get('error_currency_code');
            }

            $price = $this->request->post['price'];
            if (preg_match('/[^0-9.]/', $price) || $price == '' || !is_numeric($price)) {
                $this->data['errors_warning'][] = $this->language->get('error_prince_order');
            }

            $receiver_of_product = $this->request->post['receiver_of_product'];
            if ($receiver_of_product == '') {
                $this->data['errors_warning'][] = $this->language->get('error_receiver_of_product');
            }


            if (isset($this->request->post['pay2pay_check'])) {
                $identifier_order = $this->request->post['pay2pay_identifier_shop'];
                $key_secret = $this->request->post['pay2pay_key_secret'];
                $test_mode = $this->request->post['pay2pay_test_mode'];
                if (empty($identifier_order) || ($test_mode != 0 && $test_mode != 1) || empty($key_secret)) {
                    $this->data['errors_warning'][] = 'incorrect data pay2pay';
                }
            }
            if (isset($this->request->post['robokassa_check'])) {
                $robokassa_identifier_shop = $this->request->post['robokassa_identifier_shop'];
                $robokassa_key_secret = $this->request->post['robokassa_key_secret'];
                if (empty($robokassa_identifier_shop) || empty($robokassa_key_secret)) {
                    $this->data['errors_warning'][] = 'incorrect data robocassa';
                }
            }
            if (isset($this->request->post['interkassa_check'])) //interkassa
            {
                $identifier_order = $this->request->post['interkassa_identifier_shop'];
                if ($identifier_order == '') {
                    $this->data['errors_warning'][] = 'incorrect data intercassa';
                }
            }
        }

        if (isset($this->data['errors_warning'])) {
            return false;
        }

        //Attention
        if ($this->request->post['terminal_id'] == 1) { //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
            $get_order_id = $this->request->post['get_order_id'];
            $page_find = $this->model_sale_page_order_bobs->getPageByOrder($get_order_id);
            if (!$page_find === false) {
                $name_site = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $page_find['name_page'];
                $this->data['attentions'][] = sprintf($this->language->get('warning_duplicate_order'), $name_site);
            }
        }
        return true;

    }

    private function modifierPostToArray($post)
    {


        $array_post_parameter = array();
        foreach ($post as $key => $post_parameter) {
            $array_post_parameter[$key] = $post_parameter;
        }

        if (isset($array_post_parameter['order_alter_check'])) {
            $array_post_parameter['order_alter_check'] = 1;
        } else {
            $array_post_parameter['order_alter_check'] = 0;
        }

        if (isset($array_post_parameter['currency_code_check'])) {
            $array_post_parameter['currency_code_check'] = 1;
        } else {
            $array_post_parameter['currency_code_check'] = 0;
        }

        if (isset($array_post_parameter['pay2pay_check'])) {
            $array_post_parameter['pay2pay_check'] = 1;
        } else {
            $array_post_parameter['pay2pay_check'] = 0;
        }

        if (isset($array_post_parameter['robokassa_check'])) {
            $array_post_parameter['robokassa_check'] = 1;
        } else {
            $array_post_parameter['robokassa_check'] = 0;
        }

        if (isset($array_post_parameter['interkassa_check'])) {
            $array_post_parameter['interkassa_check'] = 1;
        } else {
            $array_post_parameter['interkassa_check'] = 0;
        }

        if (isset($array_post_parameter['alter_payment_check'])) {
            $array_post_parameter['alter_payment_check'] = 1;
        } else {
            $array_post_parameter['alter_payment_check'] = 0;
        }

        //Percent
        switch ($array_post_parameter['per_cent_of_all']) {
            case 1:
                $array_post_parameter['per_cent_of_all'] = 10;
                break;
            case 2:
                $array_post_parameter['per_cent_of_all'] = 20;
                break;
            case 3:
                $array_post_parameter['per_cent_of_all'] = 30;
                break;
            case 4:
                $array_post_parameter['per_cent_of_all'] = 40;
                break;
            case 5:
                $array_post_parameter['per_cent_of_all'] = 50;
                break;
            case 6:
                $array_post_parameter['per_cent_of_all'] = 60;
                break;
            case 7:
                $array_post_parameter['per_cent_of_all'] = 70;
                break;
            case 8:
                $array_post_parameter['per_cent_of_all'] = 80;
                break;
            case 9:
                $array_post_parameter['per_cent_of_all'] = 90;
                break;
            case 10:
                $array_post_parameter['per_cent_of_all'] = 100;
                break;
        }
        $array_post_parameter['option_client_percent'] = null;
        if (isset($post['option_client_percent'])) {
            $array_post_parameter['option_client_percent'] = serialize($post['option_client_percent']);
        } else {
            $array_post_parameter['option_client_percent'] = '';
        }
        if (!isset($post['option_client_percent_default'])) {
            $array_post_parameter['option_client_percent_default']=10;
        }


        return $array_post_parameter;
    }

    private function getLink()
    {

        $langInterface = "ru";
        $linkPay2pay = "";
        $linkRobokassa = "";
        $linkInterkassa = "";
        $order_id = $this->request->post['order_id']; // number of order
        $currency_code = $this->request->post['currency_code'];
        $price = $this->request->post['price']; //Summa
        $price = (float)$price;


        $description_order = (string)$this->request->post['description_order'];
        if (isset($this->request->post['pay2pay_check'])) {
            $identifier_order = $this->request->post['pay2pay_identifier_shop'];
            $test_mode = $this->request->post['pay2pay_test_mode'];
            $key_secret = $this->request->post['pay2pay_key_secret'];


            //Pay2pay
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
            $xml .= "<request>";
            $xml .= "<version>" . "1.2" . "</version>";
            $xml .= "<merchant_id>" . $identifier_order . "</merchant_id>";
            $xml .= "<language>" . $langInterface . "</language>";
            $xml .= "<order_id>" . $order_id . "</order_id>";
            $xml .= "<amount>" . $price . "</amount>";
            $xml .= "<currency>" . $currency_code . "</currency>";
            $xml .= "<description>" . $description_order . "</description>";
            $xml .= "<paymode><code>" . "" . "</code></paymode>";
            $xml .= "<test_mode>" . $test_mode . "</test_mode>";
            $xml .= "</request>";
            $key = $key_secret;

            $sign = md5($key . $xml . $key);

            $xml = base64_encode($xml);
            $sign = base64_encode($sign);

            $linkPay2pay = 'https://merchant.pay2pay.com/?page=init' . "&xml=" . $xml . "&sign=" . $sign;
        }
        if (isset($this->request->post['robokassa_check'])) {
            $robokassa_identifier_shop = $this->request->post['robokassa_identifier_shop'];
            $robokassa_key_secret = $this->request->post['robokassa_key_secret'];
            $robokassa_test_mode = $this->request->post['robokassa_test_mode'];
            //Robokassa
            $price_format = number_format($price, 2, '.', '');
            $crc = md5("$robokassa_identifier_shop:$price_format:$order_id:$robokassa_key_secret");
            $linkRobokassa = "https://merchant.roboxchange.com/Index.aspx?" .
                "MerchantLogin=$robokassa_identifier_shop&IsTest=$robokassa_test_mode&OutSum=$price_format&InvId=$order_id" .
                "&Desc=$description_order&SignatureValue=$crc";


        }

        if (isset($this->request->post['interkassa_check'])) //interkassa
        {
            $description_order_interkassa = $description_order;
            while ($this->getLengthStringUrl($description_order_interkassa) > 210) {
                $description_order_interkassa = substr($description_order_interkassa, 0, -5);
            }

            $identifier_order = $this->request->post['interkassa_identifier_shop'];
            $linkInterkassa = "https://sci.interkassa.com/?ik_co_id=$identifier_order&ik_pm_no=$order_id&ik_am=$price";
            if ($this->request->post['robokassa_test_mode']) {
                $linkInterkassa = $linkInterkassa . "&ik_pw_via=test_interkassa_test_xts";
            }
            $linkInterkassa = $linkInterkassa . "&ik_cur=$currency_code&ik_desc=$description_order_interkassa#/paysystemList";
        }
        $array_link = Array();
        $array_link['link_pay2pay'] = $linkPay2pay;
        $array_link['link_robokassa'] = $linkRobokassa;
        $array_link['link_interkassa'] = $linkInterkassa;
        return $array_link;

    }

    private function modifierLinkNull($array_post_parameter)
    {
        $array_post_parameter['link_pay2pay'] = '';
        $array_post_parameter['link_robokassa'] = '';
        $array_post_parameter['link_interkassa'] = '';
        return $array_post_parameter;
    }

    private function getLengthStringUrl($str_desc)
    {
        $i = substr_count($str_desc, ' ');
        $i *= 2; //space %20 - 3
        return strlen($str_desc) + $i;
    }


}

?>
