<?php

$rwpc_single_checkbox = $rwpc_productList_checkbox = array();
$rwpc_wc_single_page = $rwpc_wc_product_page = "";

if(!defined('ABSPATH')) exit;
	
if(isset($_POST['rwpc_submit'])){
	$nonce = $_POST['rwpc_wpnonce'];
	if(wp_verify_nonce( $nonce, 'rwpc_nonce' )){

		if(isset($_POST['rwpc_single_checkbox']) && !empty($_POST['rwpc_single_checkbox'])){
			$rwpc_wc_single_page	=	sanitize_text_field(json_encode($_POST['rwpc_single_checkbox']));
		}
		if(isset($_POST['rwpc_productList_checkbox']) && !empty($_POST['rwpc_productList_checkbox']) ) {
			$rwpc_wc_product_page	=	sanitize_text_field(json_encode($_POST['rwpc_productList_checkbox']));
		}

		update_option('rwpc_hide_single_wcpage_hook', $rwpc_wc_single_page);
		update_option('rwpc_hide_product_wcpage_hook', $rwpc_wc_product_page);
		
		$successmsg= success_option_msg_rwpc('Settings Saved!');
	}else{
		$errormsg= failure_option_msg_rwpc('An error has occurred.');
	}
}
	
$rwpc_hook_value=array(
	'Hide Flash Sale'				=>'woocommerce_show_product_sale_flash',
	'Hide Product Price'			=>'woocommerce_template_single_price',
	'Hide Short Desciption'			=>'woocommerce_template_single_excerpt',
	'Hide Categories and SKU'		=>'woocommerce_template_single_meta',
	'Hide Add to Cart'				=>'woocommerce_template_single_add_to_cart',
	'Hide Thumbnail Product'		=>'woocommerce_show_product_thumbnails',
	'Hide Related Product'			=>'woocommerce_output_related_products',
	'Hide Description Tab'			=>'rwpc_woocommerce_product_description_tab',
	'Hide Review Tab'				=>'rwpc_woocommerce_product_review_tab',
	'Hide Additional Information'	=>'rwpc_woocommerce_product_additional_information_tab',
	'Hide All Product Tabs'			=>'rwpc_woocommerce_product_all_tab',
);

$rwpc_shop_hook_value=array(
	'Hide Sale Flash'		=>'woocommerce_show_product_loop_sale_flash',
	'Hide Add to Cart'		=>'woocommerce_template_loop_add_to_cart',
	'Hide Price'			=>'rwpc_woocommerce_price',
	'Hide Sale Price'		=>'rwpc_woocommerce_sale_price',
	'Hide Variable Price'	=>'rwpc_woocommerce_variable_price'
);

$rwpc_get_wc_single_opt = get_option('rwpc_hide_single_wcpage_hook');
$rwpc_wc_single_value = json_decode($rwpc_get_wc_single_opt);

$rwpc_get_wc_product_opt = get_option('rwpc_hide_product_wcpage_hook');
$rwpc_wc_product_value = json_decode($rwpc_get_wc_product_opt);

?>
<div class="rwpc_wrap">
	<h1 class="rwpc-h1-title"><?php echo esc_html__('Remove Woocommerce Product Content','remove-woocommerce-product-content'); ?></h1>
	<?php
    echo (isset($successmsg)) ? $successmsg : 'Settings Saved!';
    echo (isset($errormsg)) ? $errormsg : "An error has occurred.";
    ?>
	<div class='rwpc_inner rwpc-row'>
		<div class="rwpc-col-5">
			<form method="post" class="rwpc_form">
				<table class="rwpc form-table rwpc_table">
					<thead>
						<th scope="row" class="rwpc_title_style"><?php echo esc_html__('Single Product Page','remove-woocommerce-product-content'); ?></th>
					</thead>
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="rwpc_single_page"><?php echo esc_html__('Check All','remove-woocommerce-product-content'); ?></label></th>
							<td><input name="rwpc_single_page" type="checkbox" id="rwpc_single_page" value=""></td>
						</tr>
						<?php 
						foreach($rwpc_hook_value as $rwpc_title => $rwpc_hook ){?>
							<tr valign="top">
								<th scope="row"><label for="rwpc_<?php echo $rwpc_hook; ?>"><?php echo $rwpc_title; ?></label></th>
								<td><input name="rwpc_single_checkbox[]" type="checkbox" id="rwpc_<?php echo $rwpc_hook; ?>" value="<?php echo $rwpc_hook ?>" <?php if(!empty($rwpc_wc_single_value)){ if(in_array($rwpc_hook,$rwpc_wc_single_value)){ echo "checked";}}?> ></td>
							</tr>
							<?php 
						}?>
						<thead>
							<th scope="row" class="rwpc_title_style"><?php echo esc_html__('Product List Page','remove-woocommerce-product-content'); ?></th>
						</thead>
						<tr valign="top">
							<th scope="row"><label for="rwpc_product_page"><?php echo esc_html__('Check All','remove-woocommerce-product-content'); ?></label></th>
							<td><input name="rwpc_product_page" type="checkbox" id="rwpc_product_page" value=""></td>
						</tr>
						<?php
						foreach($rwpc_shop_hook_value as $rwpc_Shop_title => $rwpc_shop_hook ){ ?>
							<tr valign="top">
								<th scope="row"><label for="rwpc_<?php echo $rwpc_shop_hook; ?>"><?php echo $rwpc_Shop_title; ?></label></th>
								<td><input name="rwpc_productList_checkbox[]" type="checkbox" id="rwpc_<?php echo $rwpc_shop_hook; ?>" value="<?php echo $rwpc_shop_hook ?>" <?php if(!empty($rwpc_wc_product_value)){ if(in_array($rwpc_shop_hook,$rwpc_wc_product_value)){ echo "checked";}}?> ></td>
							</tr>
							<?php 
						} ?>
						<input type="hidden" name="rwpc_wpnonce" value="<?php echo $nonce= wp_create_nonce('rwpc_nonce'); ?>">
						<tr valign="top">
							<td><input class="button button-primary button-large" type="submit" name="rwpc_submit" value="Update"/></td>
						</tr>	
					</tbody>
				</table>
			</form>
		</div>
		<div class="rwpc-col-7">
			<div class="rwpc-pro-features-box">
				<h3 class="rwpc-h3-title"><?php echo esc_html__('Remove Woocommerce Product Content Pro','remove-woocommerce-product-content'); ?></h3>
				<ul class="rwpc-pro-features-list">
					<li><?php echo esc_html__('Users can Hide product content individually.','remove-woocommerce-product-content'); ?></li>
					<li><?php echo esc_html__('Admin can easily remove different content of product like price, thumbnail, product tab, add to cart button etc by Category.','remove-woocommerce-product-content'); ?></li>
					<li><?php echo esc_html__('Admin can easily remove different content of product like price, thumbnail, product tab, add to cart button etc by individual product.','remove-woocommerce-product-content'); ?></li>
					<li><?php echo esc_html__('User can hide product content by single product, category.','remove-woocommerce-product-content'); ?></li>
					<li><?php echo esc_html__('Any content from above you can easily hide and show easily from admin side.','remove-woocommerce-product-content'); ?> </li>
					<li><?php echo esc_html__('Timely','remove-woocommerce-product-content'); ?> <a href="https://geekcodelab.com/contact/" target="_blank">support</a> 24/7.</li>
					<li><?php echo esc_html__('Regular updates.','remove-woocommerce-product-content'); ?></li>
					<li><?php echo esc_html__('Well documented.','remove-woocommerce-product-content'); ?></li>
				</ul>
				<a href="https://geekcodelab.com/wordpress-plugins/remove-woocommerce-product-content-pro/" class="rwpc-buy-now-btn" target="_blank"><?php echo esc_html__('Upgrade to Premium','remove-woocommerce-product-content'); ?></a>
			</div>
		</div>
	</div>
</div>