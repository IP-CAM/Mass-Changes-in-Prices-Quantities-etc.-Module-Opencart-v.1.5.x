<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if (isset($errors_warning)) { ?>
    <?php foreach ($errors_warning as $error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php } ?>

    <?php if (isset($success)) { ?>
    <?php foreach ($success as $succes) { ?>
    <div class="success"><?php echo $succes; ?></div>
    <?php } ?>
    <?php } ?>


    <?php if (isset($attentions)) { ?>
    <?php foreach ($attentions as $attention) { ?>
    <div class="attention"><?php echo $attention; ?></div>
    <?php } ?>
    <?php } ?>


    <div class="box">
    <div class="heading">
        <h1><img src="view/image/page_order_bobs.png" alt=""/>
            <?php if($page_form){ echo $heading_title;} else {echo $heading_title_link; }?>
        </h1>

        <div class="buttons">
            <?php if($page_form){ ?>
            <a class="button" id="get_save_button"><?php echo $button_save; ?></a>
            <?php }  ?>
            <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $action ?>" method="post" accept-charset="utf-8" id="form_link">
            <input id="terminal_id" type="hidden" name="terminal_id" value="0">
            <input type="hidden" name="page_host" id="page_host" value="<?php echo $page_host; ?>">
            <input type="hidden" name="one_price_total" id="one_price_total" value="<?php echo $one_price_total; ?>">
            <TABLE class="form">
                <tr>
                    <td><? echo $get_order_id_label; ?></td>
                    <td><input type="text" id="get_order_id" name="get_order_id" value="<? echo $get_order_id; ?>"/></td>
                </tr>
                <tr>
                    <td  colspan="2">
                        <?php if($page_form) { ?>
                        <a id="get_order_id_button" class="button"><?php echo $get_order_id_button_label;?></a>
                        <?php } else { ?>
                        <a id="get_order_id_link_button" class="button"><?php echo $get_order_id_button_label;?></a>
                        <?php } ?>
                    </td>
                </tr>
            </TABLE>

            <?php if(!$page_form) { ?>
            <TABLE class="form" cols="2">
                <tr>
                    <td><?php echo $link_pay2pay_label; ?></td>
                    <td><input type="text" name="link_pay2pay" size="50" value="<?php echo $link_pay2pay; ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $link_robokassa_label; ?></td>
                    <td><input type="text" name="link_robokassa" size="50" value="<?php echo $link_robokassa; ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $link_interkassa_label; ?></td>
                    <td><input type="text" name="link_interkassa" size="50" value="<?php echo $link_interkassa; ?>"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a id="get_link_button" class="button"><?php echo $button_get_link_label;?></a>
                    </td>
                </tr>
            </TABLE>
            <?php } ?>

            <TABLE class="form" cols="2">
                <tr>
                    <td><span class="required">*</span><? echo $order_id_label; ?></td>
                    <td><input type="text" name="order_id" value="<?php echo $order_id; ?>"/></td>
                </tr>
                <?php if($page_form){ ?>
                <tr>
                    <td><span class="required">*</span><? echo $name_page_label; ?></td>
                    <td>
                        <input type="text" id="name_page0" name="name_page" value="<? echo $name_page; ?>" disabled/>
                        <input type="hidden" id="name_page1" name="name_page" value="<? echo $name_page; ?>"/>
                        <a id="name_page_button" class="button" style="margin-left: 10px">
                            <?php echo $change_name_page_label; ?></a>
                    </td>

                </tr>
                <tr>
                    <td><?php echo $page_host_label ?></td>
                    <td><p id="path_page_text_full"></p></td>
                </tr>
                <tr>
                    <td><? echo $order_site_id_label; ?></td>
                    <td>
                        <input type="text" id="order_site_id" name="order_site_id" value="<?php echo $order_site_id; ?>"/>
                        <input type="checkbox" name="order_site_check" id="order_site_check"
                                <?php if($order_site_check) { ?> checked <?php }?>>
                        <?php echo $order_site_check_label; ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td><span class="required">*</span><?php echo $currency_code_label; ?></td>
                    <td>
                        <input type="text" name="currency_code" value="<?php echo $currency_code; ?>"/>
                        <?php if($page_form){ ?>
                            <input type="checkbox" name="currency_code_check" id="currency_code_check"
                            <?php if($currency_code_check) { ?> checked <?php }?>>
                            <?php echo $currency_code_check_label; ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $type_of_presentation_label; ?></td>
                    <td>
                        <input type="radio" name="type_of_presentation" value="0"
                        <?php if($type_of_presentation==0) { ?> checked="checked <?php } ?>" />
                        <?php echo $one_visible_label; ?>
                        <input type="radio" name="type_of_presentation" value="1"
                        <?php if($type_of_presentation==1) { ?> checked="checked <?php } ?>" />
                        <?php echo $several_radio_visible_label; ?>
                        <input type="radio" name="type_of_presentation" value="2"
                        <?php if($type_of_presentation==2) { ?> checked="checked <?php } ?>" />
                        <?php echo $several_link_visible_label; ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span><span id="price_label"><?php echo $price_label; ?></span></td>
                    <td>
                        <input type="text" name="price" value="<?php echo $price; ?>"/>
                        <select name="one_percent" id="one_percent_select">
                            <?php for ($i = 10; $i <= 100; $i+=10) { ?>
                            <option value="<?php echo $i ?>"
                            <?php if($one_percent==$i) { ?>
                            selected="selected" <?php } ?> ><?php echo $i ?>%</option>
                            <?php } ?>
                        </select>
                        <span id="one_percent_text">
                            <?php if(isset($one_price_total_text)) { echo $one_price_total_text; } ?>
                        </span>
                    </td>
                </tr>
                <?php if($page_form){ ?>

                    <tr id="several_percent">
                        <td><span><?php echo $option_client_percent_label; ?></span></td>
                        <td>
                            <div id="option_client_percent_block">
                                <?php for ($i = 10; $i <= 100; $i+=10) { ?>
                                <p>
                                    <span>
                                        <?php echo $option_client_percent_default_label; ?>
                                    </span>
                                    <input type="radio" name="several_percent_default" value="<?php echo $i ?>"
                                            <?php if($several_percent_default==$i){ ?> checked="checked" <?php } ?> />
                                    <input type="checkbox" name="several_percent[]" value="<?php echo $i ?>" <?php
                                        if($several_percent!=null) {
                                        if(array_search($i,$several_percent)!==false) { ?> checked="checked"<?php } } ?>>
                                    <?php echo $i ?>%
                                </p>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>

                <?php } ?>
                <tr>
                    <td><span class="required">*</span><?php echo $receiver_of_product_label; ?></td>
                    <td><input type="text" name="receiver_of_product" value="<?php echo $receiver_of_product; ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $description_order_label; ?></td>
                    <td><textarea type="text"
                                  id="description_order"
                                  name="description_order"
                                  style="margin: 0px; width: 450px; height: 92px;"><?php echo $description_order; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $delivery_address_label; ?></td>
                    <td><input type="text" name="delivery_address" value="<?php echo $delivery_address; ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $delivery_method_label; ?></td>
                    <td><input type="text" name="delivery_method" value="<?php echo $delivery_method; ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $notes_label; ?></td>
                    <td><textarea type="text"
                                  id="notes"
                                  name="notes"
                                  style="margin: 0px; width: 450px; height: 50px;"><?php echo $notes; ?></textarea>
                        <?php if(isset($notes_client)) { ?>
                        <p><ins><?php echo $notes_client;?></ins></p>
                        <?php } ?>
                    </td>
                </tr>
            </TABLE>

            <div>
                <input type="checkbox" name="pay2pay_check" id="pay2pay_check"
                <?php if($pay2pay_check) { ?>checked <?php }?>>
                <?php echo $name_payment_pay2pay_label; ?>
            </div>
            <TABLE class="form pay2pay">
                <tr>
                    <td><?php echo $identifier_shop_pay2pay_label; ?></td>
                    <td><input type="text" name="pay2pay_identifier_shop" value="<?php echo $pay2pay_identifier_shop;
                ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $key_secret_pay2pay_label; ?></td>
                    <td><input type="text" name="pay2pay_key_secret" value="<?php echo $pay2pay_key_secret; ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $test_mode_pay2pay_label; ?></td>
                    <td>
                        <?php if ($pay2pay_test_mode) { ?>
                        <input type="radio" name="pay2pay_test_mode" value="1" checked="checked" /><?php echo $text_yes; ?>
                        <input type="radio" name="pay2pay_test_mode" value="0" /><?php echo $text_no; ?>
                        <?php } else { ?>
                        <input type="radio" name="pay2pay_test_mode" value="1" /><?php echo $text_yes; ?>
                        <input type="radio" name="pay2pay_test_mode" value="0"  checked="checked"/><?php echo $text_no; ?>
                        <?php } ?>
                    </td>
                </tr>
            </TABLE>

            <div>
                <input type="checkbox" name="robokassa_check" id="robokassa_check"
                <?php if($robokassa_check) { ?>checked <?php } ?> >
                <?php echo $name_payment_robokassa_label; ?>
            </div>
            <TABLE class="form robokassa">
                <tr>
                    <td><?php echo $identifier_shop_robokassa_label; ?></td>
                    <td><input type="text" name="robokassa_identifier_shop"
                               value="<?php echo $robokassa_identifier_shop; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $key_secret_robokassa_label; ?></td>
                    <td><input type="text" name="robokassa_key_secret"
                               value="<?php echo $robokassa_key_secret; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $test_mode_robokassa_label; ?></td>
                    <td>
                        <?php if ($robokassa_test_mode) { ?>
                        <input type="radio" name="robokassa_test_mode" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                        <input type="radio" name="robokassa_test_mode" value="0" /><?php echo $text_no; ?>
                        <?php } else { ?>
                        <input type="radio" name="robokassa_test_mode" value="1" /><?php echo $text_yes; ?>
                        <input type="radio" name="robokassa_test_mode" value="0"  checked="checked"/>
                            <?php echo $text_no; ?>
                        <?php } ?>
                    </td>
                </tr>
            </TABLE>

            <div>
                <input type="checkbox" name="interkassa_check" id="interkassa_check"
                <?php if($interkassa_check) { ?>checked <?php } ?> >
                <?php echo $name_payment_interkassa_label; ?>
            </div>
            <TABLE class="form interkassa">
                <tr>
                    <td><?php echo $identifier_shop_interkassa_label; ?></td>
                    <td><input type="text" name="interkassa_identifier_shop"
                               value="<?php echo $interkassa_identifier_shop; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $test_mode_interkassa_label; ?></td>
                    <td>
                        <?php if ($interkassa_test_mode) { ?>
                        <input type="radio" name="interkassa_test_mode" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                        <input type="radio" name="interkassa_test_mode" value="0" /><?php echo $text_no; ?>
                        <?php } else { ?>
                        <input type="radio" name="interkassa_test_mode" value="1" /><?php echo $text_yes; ?>
                        <input type="radio" name="interkassa_test_mode" value="0"  checked="checked"/>
                            <?php echo $text_no; ?>
                        <?php } ?>
                    </td>
                </tr>
            </TABLE>

            <?php if($page_form) { ?>
            <div>
                <input type="checkbox"
                       name="alter_payment_check"
                       id="alter_payment_check"
                <?php if($alter_payment_check) { ?>checked <?php } ?> >
                <?php echo $alter_payment_label; ?>
            </div>
            <TABLE class="form alter_payment" <?php if(!$page_form) { echo 'hidden'; } ?> >
                <tr>
                    <td><?php echo $alter_payment_text_label; ?></td>
                    <td>
                        <textarea type="text"
                                  name="alter_payment_text"
                                  style="margin: 0px; width: 450px; height: 50px;"><?php echo $alter_payment_text; ?>
                        </textarea>
                    </td>
                </tr>
            </TABLE>
            <?php } ?>
        </form>

    </div>
    </div>



</div>
<?php echo $footer; ?>

<script type="text/javascript">

    var page_form = '<?php echo $page_form; ?>';
    var pay2pay_check = '<?php echo $pay2pay_check; ?>';
    var robokassa_check ='<?php echo $robokassa_check;?>';
    var interkassa_check ='<?php echo $interkassa_check;?>';
    var alter_payment_check ='<?php echo $alter_payment_check;?>';
    var name_page_seo ='<?php echo $name_page_seo ?>';
    var price_new_label='<?php echo $price_new_label ?>';
    var price_label='<?php echo $price_label ?>';
    var one_price_total='<?php echo $one_price_total; ?>';
    var one_percent='<?php  $e=($one_percent!=null) ? 1 : 0; echo $e; ?>';
    var old_price;

    function postPercentOne(json){
        // Get data, sent by the server, and display
        $('#description_order').val(json.description_order);
        if($('#one_percent_select option:selected').text()=='100%') {
            $('#price_label').text(price_label);
            $('[name="price"]').val(one_price_total);
            $('#one_percent_text').text(json.one_price_total_text);
        }else
        {
            $('#price_label').text(price_new_label);
            $('[name="price"]').val(json.price);
            $('#one_percent_text').text(json.one_price_total_text);
        }
    }

    function visibleTypeOfPresentation() {
        switch($('[name = "type_of_presentation"]:checked').val()) {
            case '0':
                $('#one_percent_text, [name = "one_percent"]').fadeIn();
                $('#several_percent').fadeOut();
                $.ajax({
                    url: "<?php echo $post_link; ?>",
                    dataType: 'json',
                    data: 'price=' + one_price_total + '&one_percent=' +
                    $('#one_percent_select option:selected').text() +
                    '&description_order=' + $('#description_order').val(),
                    type:'post',
                    success: postPercentOne
                });
                break;
            case '1':
            case '2':
                $('#several_percent').fadeIn();
                $('#one_percent_text, [name = "one_percent"]').fadeOut();
                $.ajax({
                    url: "<?php echo $post_link; ?>",
                    dataType: 'json',
                    data: 'price=' + one_price_total + '&one_percent=' +
                    '100%' +
                    '&description_order=' + $('#description_order').val(),
                    type:'post',
                    success: postPercentOne
                });

                break;
        }
    }

    $(document).ready(function () {

        $('#path_page_text_full').text($('#page_host').val() + $('[name = "name_page"]:first').val());
        if(!page_form || page_form==0) {
            $('[name="type_of_presentation"]').prop('disabled', true);
        }

        visibleTypeOfPresentation();

        if (!pay2pay_check) {
            $(".pay2pay").fadeOut(1000);
        }
        if (!robokassa_check) {
            $(".robokassa").fadeOut(1000);
        }
        if (!interkassa_check) {
            $(".interkassa").fadeOut(1000);
        }
        if (!alter_payment_check) {
            $(".alter_payment").fadeOut(1000);
        }

        $('#pay2pay_check').change(function () {
            $(".pay2pay").fadeToggle();
        });
        $('#robokassa_check').change(function () {
            $(".robokassa").fadeToggle();
        });
        $('#interkassa_check').change(function () {
            $(".interkassa").fadeToggle();
        });
        $('#alter_payment_check').change(function () {
            $(".alter_payment").fadeToggle();
        });


        $('[name = "order_id"]').keyup(function (eventObject) {
            var textSeo=name_page_seo.replace('%s',$('[name = "order_id"]:first').val());
            $('[name = "name_page"]').val(textSeo);
            $('#path_page_text_full').text($('#page_host').val() + textSeo);
        });
        $('[name = "name_page"]').keyup(function (eventObject) {
            $('#path_page_text_full').text($('#page_host').val() + $('[name = "name_page"]:first').val());
        });

        //Validation
        $('#get_order_id').keypress(function(key) {
            if(key.charCode < 48 || key.charCode > 57) {
                return false;
            }
        });
        $('[name = "order_id"]').keypress(function(key) {
            if(key.charCode < 48 || key.charCode > 57) {
                return false;
            }
        });
        $('[name = "order_site_id"]').keypress(function(key) {
            if(key.charCode < 48 || key.charCode > 57) {
                return false;
            }
        });

        $('[name = "price"]').keypress(function(key) {
            if( key.charCode!=46 && (key.charCode < 48 || key.charCode > 57)) {
                return false;
            }
        });

        $('[name = "price"]').blur(function() {
            if($('[name = "price"]').val()!=old_price && $('#one_percent_select option:selected').text()!='100%')
            {
                alert('<?php echo $price_modif_alert ?>');
                $('[name = "price"]').val(old_price);
            } /*else
            {
                if($('[name = "price"]').val()!=old_price)
                {
                    $.ajax({
                        url: "<?php echo $post_link; ?>",
                        dataType: 'json',
                        data: 'price=' + $('[name = "price"]').val() + '&one_percent=' +
                                $('#one_percent_select option:selected').text() +
                                '&description_order=' + $('#description_order').val(),
                        type:'post',
                        success: function(json){
                            alert('sad');
                            // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
                            $('#description_order').val(json.description_order);
                            $('#price_label').text(price_label);
                            $('[name="price"]').val(json.price);
                            $('#one_percent_text').text(json.one_price_total_text);
                            one_price_total=json.price;
                            $('#one_price_total').val(json.price);
                        }
                    });
                }*/

            }
        });
        $('[name = "price"]').focus(function() {
            old_price=$('[name = "price"]').val();
        });
        //Validation end




        $('#one_percent_select').change(function() {
                $.ajax({
                    url: "<?php echo $post_link; ?>",
                    dataType: 'json',
                    data: 'price=' + one_price_total + '&one_percent=' +
                    $('#one_percent_select option:selected').text() +
                    '&description_order=' + $('#description_order').val(),
                    type:'post',
                    success: postPercentOne
                });
        });

        $('#name_page_button').click(function () {
            $('#name_page0').prop('disabled',false);
            $('#name_page1').remove();
        });


        /*( terminal_id )
        0 - save page,
        1 - to make the data from the number order,
        2 - create link,
        3 - to make the data from the number order (form link) */
        $('#get_save_button').click(function()
                {
                    $('#terminal_id').val(0);
                    $('#form_link').submit();
                }
        );
        $('#get_order_id_button').click(function()
                {
                    $('#terminal_id').val(1);
                    $('#form_link').submit();
                }
        );
        $('#get_link_button').click(function()
                {
                    $('#terminal_id').val(2);
                    $('#form_link').submit();
                }
        );
        $('#get_order_id_link_button').click(function()
                {
                    $('#terminal_id').val(3);
                    $('#form_link').submit();
                }
        );
        $('[name = "type_of_presentation"]').click(visibleTypeOfPresentation);
    });

</script>

