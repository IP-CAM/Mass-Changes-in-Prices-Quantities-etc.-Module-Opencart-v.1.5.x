<?php

/**
 * Class ControllerSalePageOrderBobs Admin class controller Form And List page
 *
 * @author  Bobs
 * @license GPL
 */
class ControllerSalePageOrderBobs extends Controller
{

    /**
     * name page for table url_alias
     * @var string
     */
    private $name_page_seo = 'oplata-zakaz-%s';


    /**
     * Post of update one percent (page form)
     *
     * @return void
     * @author  Bobs
     *
     */
    public function post()
    {

        $this->language->load('sale/page_order_bobs');
        $json = array();
        if (!isset($this->request->post['price']) || !isset($this->request->post['one_percent']) ||
            !isset($this->request->post['description_order'])
        ) {
            exit;
        }
        $percent = substr($this->request->post['one_percent'], 0, -1);
        $price = $this->request->post['price'] * $percent / 100;
        $json['price'] = floor($price); //delete  TODO
        $prefix_damp = $this->currency->format(1000);
        $prefix = mb_substr($prefix_damp, -2, 2, 'UTF-8'); //p.
        $pattern = '/' .
            $this->language->get('one_percent_description_text') .
            '.*%, ' .
            mb_strtolower($this->language->get('price_new_label'), 'UTF-8') .
            '.*' .
            $prefix .
            '/';
        $text = $this->request->post['description_order'];
        if (preg_match($pattern, $text)) { //text empty (no)
            if ($percent == '100') { //delete text description_order
                $text = preg_replace($pattern, '', $text); //DELETE
                $pattern = '/\n$/';
                $json['description_order'] = preg_replace($pattern, '', $text);
            } else {
                $patterns = Array();
                $patterns[0] = '/' . $this->language->get('one_percent_description_text') . '.*%/';
                $patterns[1] = '/' . mb_strtolower($this->language->get('price_new_label'),
                        'UTF-8') . '.*' . $prefix . '/';
                $replace = array();
                $replace[0] = $this->language->get('one_percent_description_text') .
                    ' ' . $this->request->post['one_percent'];
                $replace[1] = mb_strtolower($this->language->get('price_new_label'), 'UTF-8') .
                    ' ' . $this->currency->format(floor($price)); //delete  TODO
                $json['description_order'] = preg_replace($patterns, $replace, $text);
            }
        } else {
            if ($percent == '100') {
                $json['description_order'] = $this->request->post['description_order'];
            } else {
                $json['description_order'] = $this->request->post['description_order'] .
                    "\n" .
                    $this->language->get('one_percent_description_text') .
                    ' ' .
                    $this->request->post['one_percent'] .
                    ', ' .
                    mb_strtolower($this->language->get('price_new_label'), 'UTF-8') .
                    ' ' .
                    $this->currency->format($price);
            }
        }
        $json['one_price_total_text'] = $this->language->get('one_price_total_text') .
            ' ' . $this->currency->format($this->request->post['price']);
        $this->response->setOutput(json_encode($json));
    }


    /**
     * The entry point in the module (page list)
     *
     * @author  Bobs
     */
    public function index()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $this->getList();
    }


    /**
     * At the entrance to change (page list)
     *
     * @author  Bobs
     */
    public function update()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $page = $this->model_sale_page_order_bobs->getPageByPage($this->request->get['page_id']);
        $this->getForm($page);
    }


    /**
     * When you create page payment (page list)
     *
     * @author  Bobs
     */
    public function insert()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');
        $page = $this->model_sale_page_order_bobs->getParameters();
        $this->getForm($page);
    }


    /**
     * Create links payment (page list)
     *
     * @author  Bobs
     */
    public function link()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $page_form = false;
        $page = $this->model_sale_page_order_bobs->getParameters();
        $this->emptyLink($page);
        $this->getForm($page, $page_form);
    }


    /**
     * Delete page payment (page list)
     *
     * @author  Bobs
     */
    public function delete()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $page_id) {
                $this->model_sale_page_order_bobs->deletePage($page_id); //Delete strong BD
            }

            $this->session->data['success'] = $this->language->get('page_delete_label'); //Messange

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

            $this->redirect($this->url->link('sale/page_order_bobs', 'token=' . $this->session->data['token'] . $url,
                'SSL'));
        }
        $this->getList();
    }


    /**
     * The Main entry point for teams from form (page form)
     *  POST['terminal_id'] :
     *  0 - save page,
     *  1 - to make the data from the number order,
     *  2 - create link,
     *  3 - to make the data from the number order (form link)
     *
     * @author  Bobs
     */
    public function terminal()
    {
        $this->language->load('sale/page_order_bobs');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/page_order_bobs');

        $this->load->model('sale/order');

        if (empty($this->request->post)) {
            $page = $this->model_sale_page_order_bobs->getPageByPage($this->request->get['page_id']);
            $this->getForm($page);
            return;
        }
        if ($this->validateForm()) {
            $array_post_parameter = $this->modifierAndAddInForm($this->request->post); //Create array
            switch ($this->request->post['terminal_id']) {
                case 0:
                    $this->addGETparametrPageId($array_post_parameter);
                    if ($this->savePage($array_post_parameter)) {
                        $this->model_sale_page_order_bobs->setParameters($array_post_parameter, true);//Save parameters
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

                        $this->redirect($this->url->link('sale/page_order_bobs',
                            'token=' . $this->session->data['token'] . $url, 'SSL'));
                    } else {
                        $this->updateForm(); //errors:
                    }
                    break;
                case 1:
                    $this->getOrderId($array_post_parameter, 1);
                    break;
                case 2:
                    $this->model_sale_page_order_bobs->setParameters($array_post_parameter);//Save parameters
                    $array_link = $this->getLink($array_post_parameter);  //Create link
                    $array_post_parameter = array_merge($array_post_parameter, $array_link);
                    $this->getForm($array_post_parameter, false);
                    break;
                case 3:
                    $this->getOrderId($array_post_parameter, 3);
                    break;
            }
        } else {
            if ($this->request->post['terminal_id'] == 0 || $this->request->post['terminal_id'] == 1) {
                $this->updateForm();
            } elseif ($this->request->post['terminal_id'] == 2 || $this->request->post['terminal_id'] == 3) {
                $this->updateForm(false);
            }
        }
    }


    /**
     * Visible List form
     *
     * @author  Bobs
     */
    protected function getList()
    {
        //LINKS BEGIN
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

        $this->data['link_form'] = $this->url->link('sale/page_order_bobs/link',
            'token=' . $this->session->data['token'], 'SSL');
        $this->data['insert'] = $this->url->link('sale/page_order_bobs/insert',
            'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('sale/page_order_bobs/delete',
            'token=' . $this->session->data['token'] . $url, 'SSL');

        //ADD LINK
        $url = '';
        if (isset($this->request->get['order'])) {
            if ($this->request->get['order'] == 'ASC') {
                $url .= '&order=DESC';
            } else {
                $url .= '&order=ASC';
            }
        } else {
            $url .= '&order=ASC'; //Default
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        $this->data['sort_page_id'] = $this->url->link('sale/page_order_bobs',
            'token=' . $this->session->data['token'] . '&sort=opd.page_id' . $url, 'SSL');
        $this->data['sort_sort_order_id'] = $this->url->link('sale/page_order_bobs',
            'token=' . $this->session->data['token'] . '&sort=opd.order_id' . $url, 'SSL');
        //LINKS END

        //PAGINATION BEGIN
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        $page_order_count = $this->model_sale_page_order_bobs->getOrderPageCount();
        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();     //Показано с 1 по 4 из 4 (всего страниц: 1)
        $pagination->total = $page_order_count;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('sale/page_order_bobs',
            'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        $this->data['pagination'] = $pagination->render();
        //PAGINATION END


        //TABLE PARAMETER BEGIN
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
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );
        $results = $this->model_sale_page_order_bobs->getPagesOrder($data);
        $server_host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        $this->data['pages'] = array();
        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('sale/page_order_bobs/update',
                    'token=' . $this->session->data['token'] . '&page_id=' . $result['page_id'] . $url, 'SSL')
            );

            if ($result['price'] == $result['one_price_total']) {
                $price = $this->currency->format($result['one_price_total']);
            } else {
                $price = $this->currency->
                    format($result['one_price_total']) .
                    $this->language->get('price_list') .
                    $result['one_percent'] . '%';
            }

            if ($this->config->get('config_seo_url')) {
                $link = $server_host . $result['keyword']; // http://site.com/oplata-zakaz-99
            } else {
                $link = $server_host .
                    'index.php?route=information/page_order_bobs&page_order_bobs_id=' .
                    $result['page_id'];
            }

            $this->data['pages'][] = array(
                'page_id' => $result['page_id'],
                'order_id' => $result['order_id'],
                'column_link_page' => $link,
                'receiver_of_product' => $result['receiver_of_product'],
                'price' => $price,
                'action' => $action
            );
        }
        //TABLE PARAMETER END

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

        $this->template = 'sale/page_order_bobs_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }


    /**
     * Visible Form Link or Page
     *
     * @param array $array_page The POST->modifierAndAddInForm()->  OR Table parameters
     * @param bool  $page_form  Page or Link visible
     * @author  Bobs
     */
    protected function getForm($array_page, $page_form = true)
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

        //LANGUAGE STRING BEGIN
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['heading_title_link'] = $this->language->get('heading_title_link');


        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_get_link_label'] = $this->language->get('button_get_link_label');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');

        $this->data['get_order_id_label'] = $this->language->get('get_order_id_label');
        $this->data['get_order_id_button_label'] = $this->language->get('get_order_id_button_label');


        $this->data['order_id_label'] = $this->language->get('order_id_label');
        $this->data['order_site_check_label'] = $this->language->get('order_site_check_label');
        $this->data['order_site_id_label'] = $this->language->get('order_site_id_label');
        $this->data['currency_code_check_label'] = $this->language->get('currency_code_check_label');
        $this->data['currency_code_label'] = $this->language->get('currency_code');
        $this->data['type_of_presentation_label'] = $this->language->get('type_of_presentation_label');
        $this->data['one_visible_label'] = $this->language->get('one_visible_label');
        $this->data['several_radio_visible_label'] = $this->language->get('several_radio_visible_label');
        $this->data['several_link_visible_label'] = $this->language->get('several_link_visible_label');

        $this->data['price_label'] = $this->language->get('price_label');
        $this->data['price_new_label'] = $this->language->get('price_new_label');
        $this->data['option_client_percent_label'] = $this->language->get('option_client_percent_label');
        $this->data['option_client_percent_default_label'] = $this->language->get('option_client_percent_default_label');
        $this->data['option_client_expand_label'] = $this->language->get('option_client_expand_label');
        $this->data['option_client_down_label'] = $this->language->get('option_client_down_label');

        $this->data['receiver_of_product_label'] = $this->language->get('receiver_of_product');
        $this->data['description_order_label'] = $this->language->get('description_order');
        $this->data['variable_text'] = $this->language->get('variable_text');
        $this->data['delivery_address_label'] = $this->language->get('delivery_address');
        $this->data['delivery_method_label'] = $this->language->get('delivery_method');


        $this->data['price_modif_alert'] = $this->language->get('price_modif_alert');
        $this->data['notes_label'] = $this->language->get('notes');
        $this->data['text_notes_up'] = $this->language->get('text_notes_up');
        $this->data['text_notes_down'] = $this->language->get('text_notes_down');

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
        //LANGUAGE STRING END

        $this->data['page_form'] = (int)$page_form;
        $this->data['page_host'] = 'http://' . $_SERVER['HTTP_HOST'] . '/'; //Name site
        $this->data['name_page_seo'] = $this->name_page_seo;


        if (!isset($array_page['get_order_id']) || !$array_page['get_order_id']) {
            $this->data['get_order_id'] = '';
        } else {
            $this->data['get_order_id'] = $array_page['get_order_id']; //getParameters
        }

        if (!isset($array_page['order_id']) || !$array_page['order_id']) {
            $this->data['order_id'] = '';
        } else {
            $this->data['order_id'] = $array_page['order_id']; //getParameters
        }

        if ($page_form) {
            $this->data['order_site_check'] = $array_page['order_site_check'];
            if (!isset($array_page['order_site_id']) || !$array_page['order_site_id']) {
                $this->data['order_site_id'] = '';
            } else {
                $this->data['order_site_id'] = $array_page['order_site_id']; //getParameters
            }
        }
        if ($page_form) {
            if (isset($array_page['name_page'])) {
                $this->data['name_page'] = $array_page['name_page'];
            } else {
                $this->data['name_page'] = sprintf($this->name_page_seo, $array_page['order_id']);
            }
        }
        $this->data['currency_code'] = $array_page['currency_code'];
        $this->data['currency_code_check'] = $array_page['currency_code_check'];


        if ($page_form) {
            $this->data['type_of_presentation'] = $array_page['type_of_presentation'];
        } else {
            $this->data['type_of_presentation'] = 0;
        }

        $this->data['one_price_total'] = $array_page['one_price_total'];
        $this->data['one_percent'] = $array_page['one_percent'];
        if ($array_page['one_price_total'] != $array_page['price']) {
            $this->data['one_price_total_text'] =
                $this->language->get('one_price_total_text') .
                ' ' .
                $this->currency->format($array_page['one_price_total']);
        }

        $this->data['several_percent_default'] =
            ($array_page['several_percent_default'] != null) ?
                $array_page['several_percent_default'] : 100;
        $this->data['several_percent'] =
            ($array_page['several_percent'] != null) ?
                unserialize($array_page['several_percent']) :
                null;

        $this->data['price'] = $array_page['price'];
        $this->data['receiver_of_product'] = $array_page['receiver_of_product'];
        $this->data['description_order'] = $array_page['description_order'];

        $this->data['variable_name'] = $array_page['variable_name'];
        $this->data['variable_value'] = $array_page['variable_value'];

        $this->data['delivery_address'] = $array_page['delivery_address'];
        $this->data['delivery_method'] = $array_page['delivery_method'];
        $this->data['notes'] = $array_page['notes'];
        $this->data['notes_up_position'] = $array_page['notes_up_position'];

        if (isset($array_page['notes_client'])) {
            $this->data['notes_client'] = $array_page['notes_client'];
        }

        $this->data['pay2pay_check'] = $array_page['pay2pay_check'];
        $this->data['pay2pay_identifier_shop'] = $array_page['pay2pay_identifier_shop'];
        $this->data['pay2pay_key_secret'] = $array_page['pay2pay_key_secret'];
        $this->data['pay2pay_test_mode'] = $array_page['pay2pay_test_mode'];

        $this->data['robokassa_check'] = $array_page['robokassa_check'];
        $this->data['robokassa_identifier_shop'] = $array_page['robokassa_identifier_shop'];
        $this->data['robokassa_key_secret'] = $array_page['robokassa_key_secret'];
        $this->data['robokassa_test_mode'] = $array_page['robokassa_test_mode'];

        $this->data['interkassa_check'] = $array_page['interkassa_check'];
        $this->data['interkassa_identifier_shop'] = $array_page['interkassa_identifier_shop'];
        $this->data['interkassa_test_mode'] = $array_page['interkassa_test_mode'];
        if ($page_form) {
            $this->data['alter_payment_check'] = $array_page['alter_payment_check'];
            $this->data['alter_payment_text'] = $array_page['alter_payment_text'];
        }
        if (!$page_form) {
            $this->data['link_pay2pay'] = $array_page['link_pay2pay'];
            $this->data['link_robokassa'] = $array_page['link_robokassa'];
            $this->data['link_interkassa'] = $array_page['link_interkassa'];
            $this->data['alter_payment_check'] = 0;
            $this->data['alter_payment_text'] = '';
        }

        //LINK
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
            $this->data['action'] = $this->url->link('sale/page_order_bobs/terminal',
                'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('sale/page_order_bobs/terminal',
                'token=' . $this->session->data['token'] . '&page_id=' . $this->request->get['page_id'] . $url, 'SSL');
        }
        $this->data['cancel'] = $this->url->link('sale/page_order_bobs',
            'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['post_link'] = 'index.php?route=sale/page_order_bobs/post&token=' . $this->session->data['token'];

        $this->template = 'sale/page_order_bobs_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }


    /**
     * Enter the order of the website in the form (page form)
     *  $id_terminal :
     *  0 - save page,
     *  1 - to make the data from the number order,
     *  2 - create link,
     *  3 - to make the data from the number order (form link)
     *
     * @param int   $id_terminal
     * @param array $array_post_parameter
     * @author  Bobs
     */
    private function getOrderId($array_post_parameter, $id_terminal)
    {
        $order = $this->model_sale_order->getOrder($array_post_parameter['get_order_id']);
        $order_products = $this->model_sale_order->getOrderProducts($array_post_parameter['get_order_id']);

        $array_post_parameter['order_id'] = $order['order_id'];
        $array_post_parameter['order_site_id'] = $order['order_id'];
        $array_post_parameter['order_site_check'] = 1;
        $array_post_parameter['name_page'] = sprintf($this->name_page_seo, $order['order_id']);
        $array_post_parameter['currency_code'] = $order['currency_code'];
        $array_post_parameter['price'] = $order['total'];
        $array_post_parameter['one_price_total'] = $order['total'];
        $array_post_parameter['one_percent'] = '100';

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
            $order_options = $this->model_sale_order->getOrderOptions($array_post_parameter['get_order_id'],
                $order_product['order_product_id']);
            if (strpos($order_product['name'], '-') != false) {
                $name = substr($order_product['name'], 0, strpos($order_product['name'], '-'));
                $name = trim($name);
                $description_order = $description_order . $name;

            } else {
                $description_order = $description_order . $order_product['name'];
            }
            $description_order .= ': ';
            $description_order .= $this->language->get('quantity');
            $description_order .= ' ';
            $description_order .= $order_product['quantity'];
            $description_order .= ', ';
            $description_order .= $this->language->get('price');
            $description_order .= ' ';
            $description_order .= $this->currency->format($order_product['price']);
            $description_order .= ', ';
            $description_order .= $this->language->get('total');
            $description_order .= ' ';
            $description_order .= $this->currency->format($order_product['total']);
            $description_order .= "\n\t";

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
            $array_post_parameter['notes_client'] = $this->language->get('notes_client_of_order') .
                ' ' . $order['comment'];
        }
        if ($id_terminal == 1) {
            $this->getForm($array_post_parameter);
        } elseif ($id_terminal == 3) {
            $this->emptyLink($array_post_parameter);
            $this->getForm($array_post_parameter, false);
        }
    }


    /**
     * Validate delete user (page list)
     *
     * @return bool
     * @author  Bobs
     */
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


    /**
     * Save or Add page payment
     *
     * @param $array_page
     * @return bool
     * @author  Bobs
     */
    private function savePage($array_page)
    {
        $array_link = Array();
        $i = 0;
        if (!empty($array_page['several_percent_array'])) {
            foreach ($array_page['several_percent_array'] as $key => $percent) {
                $ar_links = $this->getLink($array_page, $percent);
                foreach ($ar_links as $name => $link) {
                    $array_link['links'][$i]['link'] = $link;
                    $array_link['links'][$i]['percent'] = $percent;
                    $array_link['links'][$i]['type'] = $name;
                    $array_link['links'][$i]['default'] = ($array_page['several_percent_default'] == $percent ? 1 : 0);
                    $i++;
                }
            }
            $array_page = array_merge($array_link, $array_page);
        }

        $root_default_page = $this->model_sale_page_order_bobs->getParameters();
        switch ($array_page['type_of_presentation']) {
            case 0:
                $array_page['several_percent_default'] = $root_default_page['several_percent_default'];
                $array_page['several_percent'] = "";
                break;
            case 1:
            case 2:
                $array_page['one_price_total'] = $array_page['price'];
                $array_page['one_percent'] = $root_default_page['one_percent'];
                break;
        }

        if ($this->model_sale_page_order_bobs->savePage($array_page)) {
            $this->session->data['success'] = $this->language->get('success_page_save');
            return true;
        } else {
            $this->data['errors_warning'][] = 'error BD save page';
            return false;
        }
    }


    /**
     *  Validate parameters terminal (page form)
     *  $id_terminal :
     *  0 - save page,
     *  1 - to make the data from the number order,
     *  2 - create link,
     *  3 - to make the data from the number order (form link)
     *
     * @return bool
     * @author  Bobs
     */
    private function validateForm()
    {
        //ERRORS
        switch ($this->request->post['terminal_id']) {
            case 1:
            case 3:
                $get_order_id = $this->request->post['get_order_id'];
                if ($get_order_id == '') {
                    $this->data['errors_warning'][] = $this->language->get('null_number_order');
                    return false;
                }
                if (!$this->model_sale_order->getOrder($get_order_id)) {
                    $this->data['errors_warning'][] = $this->language->get('no_number_order');
                }
                break;
            case 0:
                $order_id = $this->request->post['order_id'];
                if (preg_match('/[^0-9]/', $order_id) || $order_id == '') {
                    $this->data['errors_warning'][] = $this->language->get('error_number_order');
                }

                $name_page = $this->request->post['name_page'];
                if (preg_match('/[^A-Za-zА-Яа-я0-9_\-]/', $name_page) || $name_page == '') {
                    $this->data['errors_warning'][] = $this->language->get('error_name_page');
                }
                //Есть ли имя такое же, если есть, и она ссылается не на нашу страницу, запрещяем
                if (isset($this->request->get['page_id'])) {
                    if (!$this->model_sale_page_order_bobs->emptyUrlAliasName($this->request->post['name_page'])) {
                        if ($this->model_sale_page_order_bobs->
                        emptyUrlAliasNameAndId($this->request->post['name_page'], $this->request->get['page_id'])
                        ) {
                            $this->data['errors_warning'][] = $this->language->get('error_variable_duplicate_page');
                        }
                    }
                } else { //Есть ли имя страницы такое же есть, то запрещяем создание нового
                    if (!$this->model_sale_page_order_bobs->emptyUrlAliasName($this->request->post['name_page'])) {
                        $this->data['errors_warning'][] = $this->language->get('error_duplicate_page');
                    }
                }
                $order_site_id = $this->request->post['order_site_id'];
                if (preg_match('/[^0-9]/', $order_site_id)) {
                    $this->data['errors_warning'][] = $this->language->get('error_order_site');
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

                if ($this->request->post['type_of_presentation'] == 1 || $this->request->post['type_of_presentation'] == 2) {
                    if (!isset($this->request->post['several_percent']) ||
                        !in_array($this->request->post['several_percent_default'],
                            $this->request->post['several_percent'])
                    ) {
                        $this->data['errors_warning'][] = $this->language->get('error_percent_and_default_compliance');
                    }

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
                if (isset($this->request->post['interkassa_check'])) {
                    $identifier_order = $this->request->post['interkassa_identifier_shop'];
                    if ($identifier_order == '') {
                        $this->data['errors_warning'][] = 'incorrect data intercassa';
                    }
                }
                break;
            case 2:
                $order_id = $this->request->post['order_id'];
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
                if (isset($this->request->post['interkassa_check'])) {
                    $identifier_order = $this->request->post['interkassa_identifier_shop'];
                    if ($identifier_order == '') {
                        $this->data['errors_warning'][] = 'incorrect data intercassa';
                    }
                }
                break;
        }
        if (isset($this->data['errors_warning'])) {
            return false;
        }

        //ATTENTION
        if ($this->request->post['terminal_id'] == 1) {
            $get_order_id = $this->request->post['get_order_id'];
            $name_page = $this->model_sale_page_order_bobs->getNamePageByOrder($get_order_id);
            if (!$name_page === false) {
                $name_site = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $name_page;
                $this->data['attentions'][] = sprintf($this->language->get('warning_duplicate_order'), $name_site);
            }
        }
        return true;
    }


    /**
     * Improves array
     *
     * @param array $post
     * @return array
     * @author  Bobs
     */
    private function modifierAndAddInForm($post)
    {
        $root_default_page = $this->model_sale_page_order_bobs->getParameters();

        $array_post_parameter = array();
        foreach ($post as $key => $post_parameter) {
            $array_post_parameter[$key] = $post_parameter;
        }


        if (empty($array_post_parameter['get_order_id'])) {
            $array_post_parameter['get_order_id'] = 0;
        }

        if (isset($array_post_parameter['order_site_check'])) {
            $array_post_parameter['order_site_check'] = 1;
        } else {
            $array_post_parameter['order_site_check'] = 0;
        }

        if (empty($array_post_parameter['order_site_id'])) {
            $array_post_parameter['order_site_id'] = 0;
        }

        if (isset($array_post_parameter['currency_code_check'])) {
            $array_post_parameter['currency_code_check'] = 1;
        } else {
            $array_post_parameter['currency_code_check'] = 0;
        }
        if ($array_post_parameter['terminal_id'] == 2 || $array_post_parameter['terminal_id'] == 3) //Link page
        {
            $array_post_parameter['type_of_presentation'] = $root_default_page['type_of_presentation'];
        }
        /*if (!isset($array_post_parameter['type_of_presentation']) || $array_post_parameter['type_of_presentation']=="") {
            $array_post_parameter['type_of_presentation'] = $root_default_page['type_of_presentation']; // TODO
        }*/

        if (empty($array_post_parameter['one_percent'])) {
            $array_post_parameter['one_percent'] = $root_default_page['one_percent'];
        }
        $array_post_parameter['several_percent'] = null;
        if (isset($post['several_percent'])) {
            $array_post_parameter['several_percent'] = serialize($post['several_percent']);
            $array_post_parameter['several_percent_array'] = $post['several_percent']; //Array in array
        } else {
            $array_post_parameter['several_percent'] = $root_default_page['several_percent'];
            $array_post_parameter['several_percent_array'] = unserialize($root_default_page['several_percent']);
        }

        if (empty($post['several_percent_default'])) {
            $array_post_parameter['several_percent_default'] = $root_default_page['several_percent_default'];
        }

        if ($array_post_parameter['terminal_id'] == 2 || $array_post_parameter['terminal_id'] == 3) //Link page
        {
            $array_post_parameter['variable_name'] = $root_default_page['variable_name'];
            $array_post_parameter['variable_value'] = $root_default_page['variable_value'];
            $array_post_parameter['delivery_address'] = $root_default_page['delivery_address'];
            $array_post_parameter['delivery_method'] = $root_default_page['delivery_method'];
            $array_post_parameter['notes'] = $root_default_page['notes'];
            $array_post_parameter['notes_up_position'] = $root_default_page['notes_up_position'];
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

        if (empty($array_post_parameter['alter_payment_text'])) {
            $array_post_parameter['alter_payment_text'] = $root_default_page['alter_payment_text'];
        }
        $array_post_parameter['alter_payment_text'] = trim($array_post_parameter['alter_payment_text']);
        return $array_post_parameter;
    }


    /**
     * Create Link
     *
     * @param   array $array_post_parameter
     * @param int     $percent
     * @return array
     * @author  Bobs
     */
    private function getLink($array_post_parameter, $percent = 100)
    {

        $langInterface = "ru";
        $linkPay2pay = "";
        $linkRobokassa = "";
        $linkInterkassa = "";
        $order_id = $array_post_parameter['order_id']; // number of order
        $currency_code = $array_post_parameter['currency_code'];
        $price = $array_post_parameter['price']; //Summa
        $price = (float)$price; //TODO

        $description_order = (string)$array_post_parameter['description_order'];

        if ($percent != 100) {
            $percent_label = $this->language->get('percent_label');
            $description_order .= ' ' . $percent_label . ' ' . $percent . '%';  //update
        }

        if (!empty($array_post_parameter['pay2pay_check'])) {
            $identifier_order = $array_post_parameter['pay2pay_identifier_shop'];
            $test_mode = $array_post_parameter['pay2pay_test_mode'];
            $key_secret = $array_post_parameter['pay2pay_key_secret'];

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
        if (!empty($array_post_parameter['robokassa_check'])) {
            $robokassa_identifier_shop = $array_post_parameter['robokassa_identifier_shop'];
            $robokassa_key_secret = $array_post_parameter['robokassa_key_secret'];
            $robokassa_test_mode = $array_post_parameter['robokassa_test_mode'];
            //Robokassa
            $price_format = number_format($price, 2, '.', '');
            $crc = md5("$robokassa_identifier_shop:$price_format:$order_id:$robokassa_key_secret");
            $linkRobokassa = "https://merchant.roboxchange.com/Index.aspx?" .
                "MerchantLogin=$robokassa_identifier_shop&IsTest=$robokassa_test_mode&OutSum=$price_format&InvId=$order_id" .
                "&Desc=$description_order&SignatureValue=$crc";
        }

        if (!empty($array_post_parameter['interkassa_check'])) //interkassa
        {
            $description_order_interkassa = $description_order;
            while ($this->getLengthStringUrl($description_order_interkassa) > 210) {
                $description_order_interkassa = utf8_substr($description_order_interkassa, 0, -5);
            }
            $identifier_order = $array_post_parameter['interkassa_identifier_shop'];
            $linkInterkassa = "https://sci.interkassa.com/?ik_co_id=$identifier_order&ik_pm_no=$order_id&ik_am=$price";
            if ($array_post_parameter['robokassa_test_mode']) {
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


    /**
     * Create or update empty link
     *
     * @param array $array_post_parameter
     * @author  Bobs
     */
    private function emptyLink(array &$array_post_parameter)
    {
        $array_post_parameter['link_pay2pay'] = '';
        $array_post_parameter['link_robokassa'] = '';
        $array_post_parameter['link_interkassa'] = '';
    }


    /**
     * Counts a length of the line is space
     *
     * @param string $str_desc
     * @return int
     * @author  Bobs
     */
    private function getLengthStringUrl($str_desc)
    {
        $i = mb_substr_count($str_desc, ' ');
        $i *= 2; //space %20 - 3
        return utf8_strlen($str_desc) + $i;
    }


    /**
     * Visible form
     *
     * @param bool $page_form The visible page form or links form
     * @author  Bobs
     */
    private function updateForm($page_form = true)
    {
        $array_page = $this->modifierAndAddInForm($this->request->post);
        $this->model_sale_page_order_bobs->setParameters($array_page);
        $this->getForm($array_page, $page_form);
    }


    /**
     * Add pageId for save is GET
     *
     * @param array $array_post_parameter
     * @author  Bobs
     */
    private function addGETparametrPageId(array &$array_post_parameter)
    {
        if (isset($this->request->get['page_id']) && !isset($array_post_parameter['page_id'])) {
            $array_post_parameter['page_id'] = $this->request->get['page_id'];
        }
    }

}

?>
