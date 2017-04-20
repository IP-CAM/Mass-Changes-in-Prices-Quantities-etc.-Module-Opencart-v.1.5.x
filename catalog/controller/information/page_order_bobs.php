<?php

class ControllerInformationPageOrderBobs extends Controller
{


    public function index()
    {


        $this->language->load('information/page_order_bobs');
        $this->load->model('tool/image');
        $this->load->model('account/order');
        $this->load->model('catalog/product');


        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/page_order_bobs.css')) {
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/page_order_bobs.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/page_order_bobs.css');
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        if (isset($this->request->get['page_order_bobs_id'])) {
            $page_id = (int)$this->request->get['page_order_bobs_id'];
        } else {
            $page_id = 18;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_order_bobs_description  WHERE page_id = " . (int)$page_id);
        $page = $query->row;


        if ($page) {

            $name_page = $this->language->get('title') . $page['order_id'];
            $this->document->setTitle($name_page);
            $this->data['breadcrumbs'][] = array(
                'text' => $name_page,
                'href' => $this->url->link('information/page_order_bobs', 'page_order_bobs_id=' . $page_id),
                'separator' => $this->language->get('text_separator')
            );

            //Name label
            $this->data['heading_title'] = $name_page;

            $this->data['column_image'] = $this->language->get('column_image');
            $this->data['column_name'] = $this->language->get('column_name');
            $this->data['column_model'] = $this->language->get('column_model');
            $this->data['column_quantity'] = $this->language->get('column_quantity');
            $this->data['column_price'] = $this->language->get('column_price');
            $this->data['column_total'] = $this->language->get('column_total');

            $this->data['list_payment'] = $this->language->get('list_payment');

            $this->data['link_pay2pay_label'] = $this->language->get('link_pay2pay_label');
            $this->data['link_robokassa_label'] = $this->language->get('link_robokassa_label');
            $this->data['link_interkassa_label'] = $this->language->get('link_interkassa_label');
            $this->data['currency_code_label'] = $this->language->get('currency_code_label');
            $this->data['price_label'] = $this->language->get('price_label');


            $this->data['receiver_of_product_label'] = $this->language->get('receiver_of_product');

            $this->data['delivery_address_label'] = $this->language->get('delivery_address');
            $this->data['delivery_method_label'] = $this->language->get('delivery_method');
            $this->data['notes_label'] = $this->language->get('notes');
            $order = $this->model_account_order->getOrder($page['order_alter_id']);
            if (!$page['order_alter_check']) {
                $order = 0; //No tabl? if no tabl programm
            }

            if ($order) {
                $this->data['order'] = true;


                $order_products = $this->model_account_order->getOrderProducts($order['order_id']);


                $products = Array();
                foreach ($order_products as $key => $order_product) {
                    $products[] = $order_product;
                    $product = $this->model_catalog_product->getProduct($order_product['product_id']);
                    $products[$key]['image'] = $product['image'];
                    $products[$key]['tax_class_id'] = $product['tax_class_id'];
                    //$products[]=$order_product;
                    $products[$key]['option'] = $this->model_account_order->getOrderOptions($order_product['order_id'], $order_product['order_product_id']);
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
                        $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
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


            $this->data['link_pay2pay'] = $page['link_pay2pay'];
            $this->data['link_robokassa'] = $page['link_robokassa'];
            $this->data['link_interkassa'] = $page['link_interkassa'];
            $this->data['price'] = $this->currency->format($page['price']);
            $this->data['currency_code'] = $page['currency_code'];
            $this->data['currency_code_check'] = $page['currency_code_check'];
            $this->data['receiver_of_product'] = $page['receiver_of_product'];
            $this->data['delivery_address'] = $page['delivery_address'];
            $this->data['delivery_method'] = $page['delivery_method'];
            $this->data['notes'] = $page['notes'];

            if($page['price']!=$page['price_total'])
            {
                $this->data['price_label'] = $this->language->get('price_before_present_label');
                $this->data['price']  =
                    $this->currency->format($page['price']).
                    $this->language->get('price_after_present_label').
                    $page['per_cent_of_all'].
                    '%)';
            }


            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['description_order'] = $page['description_order'];

            $this->data['continue'] = $this->url->link('common/home');

            $this->data['footer_small_label'] = $this->language->get('footer_small_label');
            $this->data['footer_label'] = $this->language->get('footer_label');
            $this->data['email_support'] = $this->config->get('config_email');


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/page_order_bobs.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/information/page_order_bobs.tpl';
            } else {
                $this->template = 'default/template/information/page_order_bobs.tpl';
            }

            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());

        } else { //Error
            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('information/page_order_bobs', 'page_order_bobs_id=' . $page_id),
                'separator' => $this->language->get('text_separator')
            );

            $this->document->setTitle($this->language->get('text_error'));

            $this->data['heading_title'] = $this->language->get('text_error');

            $this->data['text_error'] = $this->language->get('text_error');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['continue'] = $this->url->link('common/home');


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
            }

            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());
        }
    }


}

?>