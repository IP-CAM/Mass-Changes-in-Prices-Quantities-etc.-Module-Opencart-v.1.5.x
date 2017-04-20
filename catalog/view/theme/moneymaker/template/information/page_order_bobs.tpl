<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 pos-12">
                    <ul class="breadcrumb">
                        <?php foreach ($breadcrumbs as $i=> $breadcrumb) { ?>
                        <?php if($i+1<count($breadcrumbs)) { ?>
                        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                        <?php } else { ?>
                        <li class="active"><?php echo $breadcrumb['text']; ?></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php echo $content_top; ?>
            <div class="row">
                <div class="col-sm-12 pos-9">
                    <h1><?php echo $heading_title; ?></h1>
                    <br/>
                    <?php if(isset($order)) { ?>
                    <table class="list-cart">
                        <colgroup>
                            <col class="image">
                            <col class="name">
                            <col class="model">
                            <col class="quantity">
                            <col class="price">
                            <col class="total">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="image"><?php echo $column_image; ?></th>
                            <th class="name"><?php echo $column_name; ?></th>
                            <th class="model"><?php echo $column_model; ?></th>
                            <th class="quantity"><?php echo $column_quantity; ?></th>
                            <th class="price"><?php echo $column_price; ?></th>
                            <th class="total"><?php echo $column_total; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product) { ?>
                        <?php if (!empty($product['recurring'])) { ?>
                        <tr>
                            <td class="simplecheckout-recurring-product" style="border:none;"><img
                                        src="<?php echo $additional_path ?>catalog/view/theme/default/image/reorder.png"
                                        alt="" title="" style="float:left;"/>
                    <span style="float:left;line-height:18px; margin-left:10px;">
                    <strong><?php echo $text_recurring_item ?></strong>
                        <?php echo $product['profile_description'] ?>
                    </span>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="image">
                                <?php if ($product['thumb']) { ?>
                                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>"
                                                                               alt="<?php echo $product['name']; ?>"
                                                                               title="<?php echo $product['name']; ?>"/></a>
                                <?php } ?>
                            </td>
                            <td class="name">
                                <?php if ($product['thumb']) { ?>
                                <div class="image">
                                    <a href="<?php echo $product['href']; ?>"><img
                                                src="<?php echo $product['thumb']; ?>"
                                                alt="<?php echo $product['name']; ?>"
                                                title="<?php echo $product['name']; ?>"/></a>
                                </div>
                                <?php } ?>
                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>

                                <div class="options">
                                    <?php foreach ($product['option'] as $option) { ?>
                                    &nbsp;
                                    <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                    <br/>
                                    <?php } ?>
                                    <?php if (!empty($product['recurring'])) { ?>
                                    -
                                    <small><?php echo $text_payment_profile ?>
                                        : <?php echo $product['profile_name'] ?></small>
                                    <?php } ?>
                                </div>

                            </td>
                            <td class="model"><?php echo $product['model']; ?></td>
                            <td class="quantity"><?php echo $product['quantity']; ?></td>
                            <td class="price"><?php echo $product['price']; ?></td>
                            <td class="total"><?php echo $product['total']; ?></td>
                        </tr>
                        <?php } ?>
                       <?php if(isset($total_all)) { ?>
                        <tr>
                            <td class="right" colspan="5">
                                <?php echo $total_all_label; ?>
                            </td>
                            <td class="right">
                                <?php echo $total_all; ?>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php } else  { ?>
                    <?php if(isset($description_order)) { ?>
                    <p id="description"><?php echo $description_order; ?></p>
                    <?php } ?>
                    <?php } ?>

                    <h3><?php echo $list_payment; ?></h3>
                    <ul>
                        <?php if(!empty($link_pay2pay)) { ?>
                        <li><a target="_blank" href="<?php echo $link_pay2pay; ?>"><?php echo $link_pay2pay_label; ?></a></li>
                        <?php } ?>
                        <?php if(!empty($link_robokassa)) { ?>
                        <li><a target="_blank" href="<?php echo $link_robokassa; ?>"><?php echo $link_robokassa_label; ?></a></li>
                        <?php } ?>
                        <?php if(!empty($link_interkassa)) { ?>
                        <li><a target="_blank" href="<?php echo $link_interkassa; ?>"><?php echo $link_interkassa_label; ?></a></li>
                        <?php } ?>
                    </ul>


                    <TABLE class="form">
                        <?php if($currency_code_check!=0) { ?>
                        <tr>
                            <td><? echo $currency_code_label; ?></td>
                            <td><?php echo $currency_code; ?></td>
                        </tr>
                        <?php } ?>

                        <?php if($price!='') { ?>
                        <tr>
                            <td><? echo $price_label; ?></td>
                            <td><?php echo $price; ?></td>
                        </tr>
                        <?php } ?>

                        <?php if($receiver_of_product!='') { ?>
                        <tr>
                            <td><? echo $receiver_of_product_label; ?></td>
                            <td><?php echo $receiver_of_product; ?></td>
                        </tr>
                        <?php } ?>

                        <?php if($delivery_address!='') { ?>
                        <tr>
                            <td><? echo $delivery_address_label; ?></td>
                            <td><?php echo $delivery_address; ?></td>
                        </tr>
                        <?php } ?>

                        <?php if($delivery_method!='') { ?>
                        <tr>
                            <td><? echo $delivery_method_label; ?></td>
                            <td><?php echo $delivery_method; ?></td>
                        </tr>
                        <?php } ?>

                        <?php if($notes!='') { ?>
                        <tr>
                            <td><? echo $notes_label; ?></td>
                            <td><? echo $notes; ?></td>
                        </tr>
                        <?php } ?>

                    </TABLE>

                 <!--   <div class="row pagination_buttons">
                        <div class="col-xs-12 text-center">
                            <p><a class="btn btn-lg btn-primary"
                                  href="<?php echo $continue; ?>"><?php echo $button_continue; ?></a></p>
                        </div>
                    </div> -->
                    <p><?php echo $footer_label;?>
                        <a href="mailto:<?php echo $email_support; ?>"><?php echo $email_support; ?></a></p>

                    <small><?php echo $footer_small_label; ?></small>

                </div>
            </div>
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>
<?php echo $footer; ?>