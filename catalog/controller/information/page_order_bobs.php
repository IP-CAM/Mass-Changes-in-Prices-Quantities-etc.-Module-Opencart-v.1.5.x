<?php

/**
 * Class ControllerInformationPageOrderBobs front class controller
 *
 * @author  Bobs
 * @license GPL
 */
class ControllerInformationPageOrderBobs extends Controller
{

    /**
     * Return link for radio type presentation
     * @author  Bobs
     */
    public function post()
    {
        $json = $this->getPostLink($this->request->post['order_id'], $this->request->post['percent']);
        $this->response->setOutput(json_encode($json));
    }


    /**
     * Return Links
     * @param int $order_id
     * @param int $percent
     * @return array
     * @author  Bobs
     */
    private function getPostLink($order_id, $percent)
    {
        $sql = "SELECT `page_id` FROM `" . DB_PREFIX . "page_order_bobs_description` WHERE `order_id` LIKE " . (int)$order_id;
        $page = $this->db->query($sql);
        $page_id = $page->row['page_id'];
        $sql = "SELECT * FROM `" .
            DB_PREFIX . "page_order_bobs_links`  WHERE `page_id`=" . (int)$page_id . " AND `percent`=" . (int)$percent;
        $links = $this->db->query($sql);
        $array_link = Array();
        foreach ($links->rows as $link) {
            if ($link['percent']) {
                $array_link[$link['type']] = $link['link'];
            }
        }
        return $array_link;
    }


    /**
     * Main point
     *
     * @author  Bobs
     */
    public function index()
    {
        $this->loadModelAndCssStyle();
        $page_id = (int)$this->request->get['page_order_bobs_id'];
        $page = $this->getPage($page_id);
        if ($page) {
            $this->viewPage($page, $page_id);
        } else { //Error
            $this->viewErrorNonFound($page_id);
        }
    }


    /**
     * Load modul and css style
     *
     * @author  Bobs
     */
    private function loadModelAndCssStyle()
    {
        $this->language->load('information/page_order_bobs');
        $this->load->model('tool/image');
        $this->load->model('account/order');
        $this->load->model('catalog/product');
        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') .
            '/stylesheet/page_order_bobs.css')) {
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') .
                '/stylesheet/page_order_bobs.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/page_order_bobs.css');
        }
    }

    /**
     * Return data page
     *
     * @param int $page_id
     * @return array
     * @author  Bobs
     */
    private function getPage($page_id)
    {
        $sql = "SELECT * FROM `" .
            DB_PREFIX . "page_order_bobs` op
                LEFT JOIN `" .
            DB_PREFIX . "page_order_bobs_description` opd
                ON
                (op.page_id = opd.page_id) WHERE op.page_id=" . (int)$page_id;
        $query = $this->db->query($sql);
        $page = $query->row;
        $sql = "SELECT * FROM `" .
            DB_PREFIX . "page_order_bobs_links`  WHERE `page_id`=" . (int)$page_id;
        $links = $this->db->query($sql);
        $links = $links->rows;
        $page['links'] = $links;
        return $page;
    }


    /**
     * View page
     *
     * @param array $page
     * @param int   $page_id
     * @author  Bobs
     */
    private function viewPage($page, $page_id)
    {
        $this->pageIsDataHeader($page, $page_id);
        $this->pageIsDataOrder($page['order_site_id']);
        $this->pageIsDataBody($page, $page_id);
        $this->pageIsDataFooter();
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/page_order_bobs.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/information/page_order_bobs.tpl';
        } else {
            $this->template = 'default/template/information/page_order_bobs.tpl';
        }
        $this->response->setOutput($this->render());
    }


    /**
     * Record variable for Header
     *
     * @param array $page
     * @param   int    $page_id
     * @author  Bobs
     */
    private function pageIsDataHeader(array &$page, $page_id)
    {
        $name_page = $this->language->get('title') . $page['order_id'];
        $this->document->setTitle($name_page);
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $name_page,
            'href' => $this->url->link('information/page_order_bobs', 'page_order_bobs_id=' . $page_id),
            'separator' => $this->language->get('text_separator')
        );
        $this->data['heading_title'] = $name_page;
    }


    /**
     * Record variable for Order
     *
     * @param int $order_site_id
     * @author  Bobs
     */
    private function pageIsDataOrder($order_site_id)
    {
        $order = $this->getOrder($order_site_id);
        if ($order) {
            $this->data['column_image'] = $this->language->get('column_image');
            $this->data['column_name'] = $this->language->get('column_name');
            $this->data['column_model'] = $this->language->get('column_model');
            $this->data['column_quantity'] = $this->language->get('column_quantity');
            $this->data['column_price'] = $this->language->get('column_price');
            $this->data['column_total'] = $this->language->get('column_total');

            $this->data['order'] = true;
            $order_products = $this->model_account_order->getOrderProducts($order['order_id']);
            $products = Array();
            foreach ($order_products as $key => $order_product) {
                $products[] = $order_product;
                $product = $this->model_catalog_product->getProduct($order_product['product_id']);
                $products[$key]['image'] = $product['image'];
                $products[$key]['tax_class_id'] = $product['tax_class_id'];
                //$products[]=$order_product;
                $products[$key]['option'] = $this->model_account_order->getOrderOptions($order_product['order_id'],
                    $order_product['order_product_id']);
            }

            foreach ($products as $product) {
                $option_data = array();
                $points_total = 0;
                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $encryption = new Encryption($this->config->get('config_encryption'));
                        $option_value = $encryption->decrypt($option['value']);
                        $filename = substr($option_value, 0, strrpos($option_value, '.'));
                        $value = $filename;
                    }
                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 200 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                if ($product['image']) {
                    $image_cart_width = $this->config->get('config_image_cart_width');
                    $image_cart_width = $image_cart_width ? $image_cart_width : 40;
                    $image_cart_height = $this->config->get('config_image_cart_height');
                    $image_cart_height = $image_cart_height ? $image_cart_height : 40;
                    $image = $this->model_tool_image->resize($product['image'], $image_cart_width, $image_cart_height);
                } else {
                    $image = '';
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'],
                        $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'],
                            $this->config->get('config_tax')) * $product['quantity']);
                } else {
                    $total = false;
                }

                $this->data['products'][] = array(
                    'thumb' => $image,
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $price,
                    'total' => $total,
                    'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );

            }

            if (count($order_products) > 1) {
                $this->data['total_all'] = $this->currency->format($order['total']);
                $this->data['total_all_label'] = $this->language->get('total_all_label');

            }
        } else  //no order product free page order
        {
            if (!empty($page['description'])) {
                $this->data['description'] = $page['description'];
            }
        }
    }


    /**
     * Return order array
     *
     * @param int $order_site_id
     * @return array
     * @author  Bobs
     */
    private function getOrder($order_site_id)
    {
        $order = $this->model_account_order->getOrder($order_site_id);
        if (!$order_site_id) {
            $order = 0; //No tabl, if no tabl programm
        }
        return $order;
    }


    /**
     * Record variable for Body
     *
     * @param array $page
     * @author  Bobs
     */
    private function pageIsDataBody(array &$page)
    {
        $this->data['list_payment_label'] = $this->language->get('list_payment_label');
        $this->data['link_pay2pay_label'] = $this->language->get('link_pay2pay_label');
        $this->data['link_robokassa_label'] = $this->language->get('link_robokassa_label');
        $this->data['link_interkassa_label'] = $this->language->get('link_interkassa_label');

        $this->data['currency_code_label'] = $this->language->get('currency_code_label');
        $this->data['price_label'] = $this->language->get('price_label');
        $this->data['several_percent_label'] = $this->language->get('several_percent_label');
        $this->data['several_percent_variable'] = $this->language->get('several_percent_variable');

        $this->data['receiver_of_product_label'] = $this->language->get('receiver_of_product_label');
        $this->data['delivery_address_label'] = $this->language->get('delivery_address_label');
        $this->data['delivery_method_label'] = $this->language->get('delivery_method_label');
        $this->data['notes_label'] = $this->language->get('notes_label');

        $this->data['order_id'] = $page['order_id'];
        $this->data['type_of_presentation'] = $page['type_of_presentation'];
        $this->data['price_label'] = $this->language->get('price_before_one_percent_label');
        switch ($page['type_of_presentation']) {
            case 0:
                if ($page['price'] == $page['one_price_total']) {
                    $this->data['price'] = $this->currency->format($page['price']);
                } else {
                    $this->data['price'] =
                        $this->currency->format($page['price']) .
                        $this->language->get('price_after_one_percent_label') .
                        $page['one_percent'] .
                        '%)';
                }
                foreach ($page['links'] as $link) {
                    if ($link['default']) {
                        $this->data[$link['type']] = $link['link'];
                    }
                }
                break;
            case 1:
                $this->data['several_percent_default'] = $page['several_percent_default'];
                $this->data['several_percent'] = $page['several_percent'];
                if ($page['several_percent'] != null) {
                    $several_percent = unserialize($page['several_percent']);
                    $options_client_percent_general = array();
                    foreach ($several_percent as $key => $percent) {
                        $options_client_percent_general[$key]['percent'] = $percent;
                        $options_client_percent_general[$key]['price'] = $this->currency->format(floor(($page['one_price_total'] * $percent) / 100)); //delete  TODO
                    }
                    $this->data['several_percent'] = $options_client_percent_general;
                } else {
                    $this->data['several_percent'] = '';
                }
                $this->data['price'] = $this->currency->format($page['price']);
                foreach ($page['links'] as $link) {
                    if ($link['default']) {
                        $this->data[$link['type']] = $link['link'];
                    }
                }
                break;
            case 2:
                $this->data['price'] = null;
                $links_structure = array();
                foreach ($page['links'] as $link) {

                    if (array_key_exists($link['percent'], $links_structure)) {
                        $links_structure[$link['percent']][] = $link;
                    } else {
                        $links_structure[$link['percent']] = array();
                        $links_structure[$link['percent']][] = $link;
                    }
                }
                $this->data['links_structure'] = $links_structure;
                break;
        }

        $this->data['pay2pay_check'] = $page['pay2pay_check'];
        $this->data['robokassa_check'] = $page['robokassa_check'];
        $this->data['interkassa_check'] = $page['interkassa_check'];
        $this->data['alter_payment_check'] = $page['alter_payment_check'];

        $this->data['alter_payment_text'] = $page['alter_payment_text'];

        $this->data['currency_code'] = $page['currency_code'];
        $this->data['currency_code_check'] = $page['currency_code_check'];
        $this->data['receiver_of_product'] = $page['receiver_of_product'];
        $this->data['delivery_address'] = $page['delivery_address'];
        $this->data['delivery_method'] = $page['delivery_method'];
        $this->data['notes'] = $page['notes'];

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['description_order'] = $page['description_order'];

        $this->data['continue'] = $this->url->link('common/home');

        $this->data['footer_small_label'] = $this->language->get('footer_small_label');
        $this->data['footer_label'] = $this->language->get('footer_label');
        $this->data['email_support'] = $this->config->get('config_email');
    }


    /**
     * Record variable for Footer
     *
     * @author  Bobs
     */
    private function pageIsDataFooter()
    {
        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );
    }


    /**
     * View page non Found
     *
     * @param $page_id
     * @author  Bobs
     */
    private function viewErrorNonFound($page_id)
    {
        $this->pageErrorNonFoundIsDataHeader($page_id);
        $this->pageErrorNonFoundIsDataBody();
        $this->pageErrorNonFoundIsDataFooter();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
        } else {
            $this->template = 'default/template/error/not_found.tpl';
        }

        $this->response->setOutput($this->render());
    }


    /**
     * Record variable for Header Non Found
     *
     * @param int $page_id
     * @author  Bobs
     */
    private function pageErrorNonFoundIsDataHeader($page_id)
    {
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_error'),
            'href' => $this->url->link('information/page_order_bobs', 'page_order_bobs_id=' . $page_id),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->setTitle($this->language->get('text_error'));

        $this->data['heading_title'] = $this->language->get('text_error');
    }


    /**
     * Record variable for Body  Non Found
     *
     * @author  Bobs
     */
    private function pageErrorNonFoundIsDataBody()
    {
        $this->data['text_error'] = $this->language->get('text_error');
        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['continue'] = $this->url->link('common/home');
    }


    /**
     * Record variable for Footer Non Found
     *
     * @author  Bobs
     */
    private function pageErrorNonFoundIsDataFooter()
    {
        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );
    }


}


?>