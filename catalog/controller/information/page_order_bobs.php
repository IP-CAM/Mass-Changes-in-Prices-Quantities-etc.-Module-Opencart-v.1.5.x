<?php

class ControllerInformationPageOrderBobs extends Controller
{
    public function post()
    {

          $json = array();
        $json=$this->getLink($this->request->post['order_id'],$this->request->post['percent']);
        $this->response->setOutput(json_encode($json));

    }

    private function getLink($order_id, $percent)
    {
        $this->language->load('information/page_order_bobs');
        $percent_label = $this->language->get('percent_label');
        $sql = "SELECT * FROM `" . DB_PREFIX . "page_order_bobs_description` WHERE `order_id` LIKE " . (int)$order_id;
        $obj_sql_page = $this->db->query($sql);
        if ($obj_sql_page->num_rows == 0) {
            return false;
        }
        $page=$obj_sql_page->row;

        $langInterface = "ru";
        $linkPay2pay = "";
        $linkRobokassa = "";
        $linkInterkassa = "";

        $currency_code = $page['currency_code'];
        $price = $page['price_total']; //Summa
        $price = ((float)$price*(int)$percent)/100; //update
        $price = floor($price); //delete  TODO

        $description_order = (string)$page['description_order'];
        $description_order.=' '.$percent_label.' '.$percent.'%';  //update
        if ($page['pay2pay_check']) {
            $identifier_order = $page['pay2pay_identifier_shop'];
            $test_mode = $page['pay2pay_test_mode'];
            $key_secret = $page['pay2pay_key_secret'];


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
        if ($page['robokassa_check']) {
            $robokassa_identifier_shop = $page['robokassa_identifier_shop'];
            $robokassa_key_secret = $page['robokassa_key_secret'];
            $robokassa_test_mode = $page['robokassa_test_mode'];
            //Robokassa
            $price_format = number_format($price, 2, '.', '');
            $crc = md5("$robokassa_identifier_shop:$price_format:$order_id:$robokassa_key_secret");
            $linkRobokassa = "https://merchant.roboxchange.com/Index.aspx?" .
                "MerchantLogin=$robokassa_identifier_shop&IsTest=$robokassa_test_mode&OutSum=$price_format&InvId=$order_id" .
                "&Desc=$description_order&SignatureValue=$crc";


        }

        if ($page['interkassa_check']) //interkassa
        {
            $description_order_interkassa = $description_order;
            while ($this->getLengthStringUrl($description_order_interkassa) > 210) {
                $description_order_interkassa = utf8_substr($description_order_interkassa, 0, -5);
            }

            $identifier_order = $page['interkassa_identifier_shop'];
            $linkInterkassa = "https://sci.interkassa.com/?ik_co_id=$identifier_order&ik_pm_no=$order_id&ik_am=$price";
            if ($page['robokassa_test_mode']) {
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
            $this->data['option_client_percent_label'] = $this->language->get('option_client_percent_label');
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
                //if is store no empty order
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

            $this->data['order_id'] = $page['order_id'];
            $this->data['option_client_percent_default'] = $page['option_client_percent_default'];
            if($page['option_client_percent']!=null)
            {
                $options_client_percent = unserialize($page['option_client_percent']);
                $options_client_percent_general=array();
                foreach($options_client_percent as $key=>$option_client_percent)
                {
                    $options_client_percent_general[$key]['percent']=$option_client_percent;
                    $options_client_percent_general[$key]['price']=$this->currency->format(floor(($page['price_total']*$option_client_percent)/100)); //delete  TODO
                }
                $this->data['option_client_percent']=$options_client_percent_general;
            }else{
                $this->data['option_client_percent']=null;
            }
            $this->data['alter_payment_check'] = $page['alter_payment_check'];
            $this->data['alter_payment_text'] = $page['alter_payment_text'];
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
    private function getLengthStringUrl($str_desc)
    {
        $i = mb_substr_count($str_desc, ' ');
        $i *= 2; //space %20 - 3
        return utf8_strlen($str_desc) + $i;
    }

}

?>