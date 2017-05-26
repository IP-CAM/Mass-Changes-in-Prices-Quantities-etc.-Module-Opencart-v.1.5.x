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
            <input id="terminal_id" type="hidden" name="terminal_id" value="0"> <!-- //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)-->
            <input type="hidden" name="language_id" value="<?php echo $language_id; ?>">
            <input type="hidden" name="page_host" id="page_host" value="<?php echo $page_host; ?>">
            <input type="hidden" name="price_total" id="price_total" value="<?php echo $price_total; ?>">
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
                    <td><? echo $order_alter_id_label; ?></td>
                    <td>
                        <input type="text" id="order_alter_id" name="order_alter_id" value="<?php echo $order_alter_id; ?>"/>
                        <input type="checkbox" name="order_alter_check" id="order_alter_check" <?php if($order_alter_check) { ?> checked <?php }?>>
                        <?php echo $order_alter_check_label; ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td><span class="required">*</span><?php echo $currency_code_label; ?></td>
                    <td>
                        <input type="text" name="currency_code" value="<?php echo $currency_code; ?>"/>
                        <?php if($page_form){ ?>
                        <input type="checkbox" name="currency_code_check" id="currency_code_check" <?php if($currency_code_check) { ?> checked <?php }?>>
                        <?php echo $currency_code_check_label; ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $type_of_presentation_label; ?></td>
                    <td>
                        <input type="radio" name="type_of_presentation" value="0" <?php if
                        ($type_of_presentation==0) { ?> checked="checked <?php } ?>" />
                        <?php echo $one_visible_label; ?>
                        <input type="radio" name="type_of_presentation" value="1" <?php if
                        ($type_of_presentation==1) { ?> checked="checked <?php } ?>" />
                        <?php echo $several_visible_label; ?>
                        <input type="radio" name="type_of_presentation" value="2" <?php if
                        ($type_of_presentation==2) { ?> checked="checked <?php } ?>" />
                        <?php echo $one_visible_label; ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span><span id="price_label"><?php echo $price_label; ?></span></td>
                    <td>
                        <input type="text" name="price" value="<?php echo $price; ?>"/>
                        <select name="per_cent_of_all" id="per_cent_of_all_select">
                            <option value="1" <?php if($per_cent_of_all==10) { ?> selected="selected"<?php } ?>>10%</option>
                            <option value="2" <?php if($per_cent_of_all==20) { ?> selected="selected"<?php } ?>>20%</option>
                            <option value="3" <?php if($per_cent_of_all==30) { ?> selected="selected"<?php } ?>>30%</option>
                            <option value="4" <?php if($per_cent_of_all==40) { ?> selected="selected"<?php } ?>>40%</option>
                            <option value="5" <?php if($per_cent_of_all==50) { ?> selected="selected"<?php } ?>>50%</option>
                            <option value="6" <?php if($per_cent_of_all==60) { ?> selected="selected"<?php } ?>>60%</option>
                            <option value="7" <?php if($per_cent_of_all==70) { ?> selected="selected"<?php } ?>>70%</option>
                            <option value="8" <?php if($per_cent_of_all==80) { ?> selected="selected"<?php } ?>>80%</option>
                            <option value="9" <?php if($per_cent_of_all==90) { ?> selected="selected"<?php } ?>>90%</option>
                            <option value="10" <?php if($per_cent_of_all==100) { ?> selected="selected"<?php } ?>>100%</option>
                        </select>
                        <span id="per_cent_of_all_text"><?php if(isset($price_total_text)) { echo $price_total_text; } ?></span>
                    </td>
                </tr>
                <?php if($page_form){ ?>
                <tr>
                    <td><span><?php echo $option_client_percent_label; ?></span></td>
                    <td>
                        <a  id="expand_down"></a>
                        <div id="option_client_percent_block">
                            <?php for ($i = 10; $i <= 100; $i+=10) { ?>
                            <p><span>
                                <?php echo $option_client_percent_default_label; ?>
                            </span>
                                <input type="radio" name="option_client_percent_default" value="<?php echo $i ?>" <?php if($option_client_percent_default==$i){ ?> checked="checked" <?php } ?> />
                                <input type="checkbox" name="option_client_percent[]" value="<?php echo $i ?>" <?php
                            if($option_client_percent!=null) {
                            if(array_search($i,$option_client_percent)!==false) { ?> checked="checked"<?php } } ?>><?php echo $i ?>%
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
               <!-- <tr>
                    <td><?php echo $test_mode_pay2pay_label; ?></td>
                    <td><input type="text" name="pay2pay_test_mode" value="<?php echo $pay2pay_test_mode; ?>"/></td>
                </tr> -->
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
                        <input type="radio" name="robokassa_test_mode" value="1" checked="checked" /><?php echo $text_yes; ?>
                        <input type="radio" name="robokassa_test_mode" value="0" /><?php echo $text_no; ?>
                        <?php } else { ?>
                        <input type="radio" name="robokassa_test_mode" value="1" /><?php echo $text_yes; ?>
                        <input type="radio" name="robokassa_test_mode" value="0"  checked="checked"/><?php echo $text_no; ?>
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
                        <input type="radio" name="interkassa_test_mode" value="1" checked="checked" /><?php echo $text_yes; ?>
                        <input type="radio" name="interkassa_test_mode" value="0" /><?php echo $text_no; ?>
                        <?php } else { ?>
                        <input type="radio" name="interkassa_test_mode" value="1" /><?php echo $text_yes; ?>
                        <input type="radio" name="interkassa_test_mode" value="0"  checked="checked"/><?php echo $text_no; ?>
                        <?php } ?>
                    </td>
                </tr>
            </TABLE>

            <div <?php if(!$page_form) { echo 'hidden'; } ?>>
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
        </form>


    </div>
    </div>



</div>
<?php echo $footer; ?>

<script type="text/javascript">

    var page_form = <?php echo $page_form; ?>;
    var pay2pay_check = <?php echo $pay2pay_check; ?>;
    var robokassa_check =<?php echo $robokassa_check;?>;
    var interkassa_check =<?php echo $interkassa_check;?>;
    var alter_payment_check =<?php echo $alter_payment_check;?>;
    var name_page_seo ='<?php echo $name_page_seo ?>';
    var price_new_label='<?php echo $price_new_label ?>';
    var price_label='<?php echo $price_label ?>';
    var price_total='<?php echo $price_total; ?>';
    var option_client_percent='<?php  $e=($option_client_percent!=null) ? 1 : 0; echo $e; ?>';
    var old_price;
    var checkFableOptionPercent;
    var option_client_down_label='<?php echo $option_client_down_label; ?>';
    var option_client_expand_label='<?php echo $option_client_expand_label; ?>';
    $(document).ready(function () {
        if(option_client_percent==1)
        {
            $('#expand_down').text(option_client_down_label);
            checkFableOptionPercent=true;
        } else {
            $('#expand_down').text(option_client_expand_label);
            $("#option_client_percent_block").fadeOut(0);
            checkFableOptionPercent=false;
        }
        $('#path_page_text_full').text($('#page_host').val() + $('[name = "name_page"]:first').val());

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
        $('[name = "price"]').keypress(function(key) {
            if( key.charCode!=46 && (key.charCode < 48 || key.charCode > 57)) {
                return false;
            }
        });

        $('[name = "price"]').blur(function() {
            if($('[name = "price"]').val()!=old_price && $('#per_cent_of_all_select option:selected').text()!='100%')
            {
                alert('<?php echo $price_modif_alert ?>');
                $('[name = "price"]').val(old_price);
            } else
            {
                if($('[name = "price"]').val()!=old_price)
                {
                    $.ajax({
                        url: "<?php echo $post_link; ?>",
                        dataType: 'json',
                        data: 'price=' + $('[name = "price"]').val() + '&percent=' + $('#per_cent_of_all_select option:selected').text() + '&description_order=' + $('#description_order').val(),
                        type:'post',
                        success: function(json){
                            // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
                            $('#description_order').val(json.description_order);
                            $('#price_label').text(price_label);
                            $('[name="price"]').val(json.price);
                            $('#per_cent_of_all_text').text(json.price_total_text);
                            price_total=json.price;
                            $('#price_total').val(json.price);
                        }
                    });
                }

            }
        });
        $('[name = "price"]').focus(function() {
            old_price=$('[name = "price"]').val();
        });



        //Validation end

        $('#per_cent_of_all_select').change(function() {

                $.ajax({
                    url: "<?php echo $post_link; ?>",
                    dataType: 'json',
                    data: 'price=' + price_total + '&percent=' + $('#per_cent_of_all_select option:selected').text() + '&description_order=' + $('#description_order').val(),
                    type:'post',
                    success: function(json){
                        // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
                        $('#description_order').val(json.description_order);
                        if($('#per_cent_of_all_select option:selected').text()=='100%') {
                            $('#price_label').text(price_label);
                            $('[name="price"]').val(price_total);
                            $('#per_cent_of_all_text').text(json.price_total_text);
                        }else
                        {
                            $('#price_label').text(price_new_label);
                            $('[name="price"]').val(json.price);
                            $('#per_cent_of_all_text').text(json.price_total_text);
                        }
                    }
                });

         //   $('#per_cent_of_all_select option:selected').text();
        });

        $('#name_page_button').click(function () {
            $('#name_page0').prop('disabled',false);
            $('#name_page1').remove();


        });

        $('#get_save_button').click(function()
                {
                    $('#terminal_id').val(0); //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
                    $('#form_link').submit();
                }
        );
        $('#get_order_id_button').click(function()
                {
                    $('#terminal_id').val(1); //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
                    $('#form_link').submit();
                }
        );
        $('#get_link_button').click(function()
                {
                    $('#terminal_id').val(2); //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
                    $('#form_link').submit();
                }
        );
        $('#get_order_id_link_button').click(function()
                {
                    $('#terminal_id').val(3); //( terminal_id ) 0 - save page, 1 - to make the data from the number order, 2 - create link, 3 - to make the data from the number order (form link)
                    $('#form_link').submit();
                }
        );

        $('#expand_down').click(function() {
            if(checkFableOptionPercent) {
                $("#option_client_percent_block").fadeOut(300);
                $('#expand_down').text(option_client_expand_label);
                checkFableOptionPercent=false;
            } else {
                $("#option_client_percent_block").fadeIn(300);
                $('#expand_down').text(option_client_down_label);
                checkFableOptionPercent=true;
            }

        });

    });





</script>

