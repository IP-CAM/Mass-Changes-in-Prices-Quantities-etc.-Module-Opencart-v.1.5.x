<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/page_order_bobs.png" alt=""/> <?php echo $heading_title; ?></h1>

            <div class="buttons">
                <a href="<?php echo $link_form; ?>" class="button"><?php echo $button_link_form_label; ?></a>
                <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert_label; ?></a>
                <a onclick="$('form').submit();" class="button"><?php echo $button_delete_label; ?></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="list">
                    <thead>
                    <tr>
                        <td width="1" style="text-align: center;"><input type="checkbox"
                             onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                        </td>
                        <td class="center" style="width: 30px;"><?php if ($sort == 'opd.page_id') { ?>
                            <a href="<?php echo $sort_page_id; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_page_id_label; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_page_id; ?>"><?php echo $column_page_id_label; ?></a>
                            <?php } ?>
                        </td>
                        <td class="left"><?php if ($sort == 'opd.order_id') { ?>
                            <a href="<?php echo $sort_sort_order_id; ?>"
                               class="<?php echo strtolower($order); ?>"><?php echo $column_order_id_label; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_sort_order_id; ?>"><?php echo $column_order_id_label; ?></a>
                            <?php } ?>
                        </td>
                        <td class="left"><?php echo $column_link_page_label; ?></td>
                        <td class="right"><?php echo $column_receiver_of_product_label; ?></td>
                        <td class="right"><?php echo $column_price_label; ?></td>
                        <td class="right"><?php echo $column_action_label; ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($pages) { ?>
                    <?php foreach ($pages as $page) { ?>
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="selected[]" value="<?php echo $page['page_id']; ?>"/>
                        </td>
                        <td class="center"><?php echo $page['page_id']; ?></td>
                        <td class="left"><?php echo $page['order_id']; ?></td>
                        <td class="left"><a href="<?php echo $page['column_link_page']; ?>" target="_blank">
                                <?php echo $page['column_link_page']; ?></a>
                        </td>
                        <td class="right"><?php echo $page['receiver_of_product']; ?></td>
                        <td class="right"><?php echo $page['price']; ?></td>
                        <td class="right"><?php foreach ($page['action'] as $action) { ?>
                            [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                            <?php } ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="center" colspan="7"><?php echo $text_no_results_label; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>
<?php echo $footer; ?>