<?php
/*
Plugin Name: Remove Woocommerce Product Content
Description: Hide product content from  wooocommerce Single product page, Shop page and category page
Author: Geek Code Lab
Version: 2.2
WC tested up to: 8.6.1
Author URI: https://geekcodelab.com/
Text Domain: remove-woocommerce-product-content
*/
if(!defined('ABSPATH')) exit;

define("RWPC_BUILD","2.2");

if(!defined("RWPC_PLUGIN_DIR_PATH"))
	
	define("RWPC_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
	
if(!defined("RWPC_PLUGIN_URL"))
	
	define("RWPC_PLUGIN_URL", plugins_url().'/'.basename(dirname(__FILE__)));
	
require_once( RWPC_PLUGIN_DIR_PATH .'functions.php');

add_action('admin_menu', 'rwpc_admin_option' );

add_action('admin_enqueue_scripts','rwpc_enqueue_styles');

add_action('admin_init', 'rwpc_registerSettings');

function rwpc_plugin_add_settings_link( $links ) { 
	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __( 'Support', 'remove-woocommerce-product-content' ) . '</a>';
	array_unshift( $links, $support_link );
	
	$pro_link = '<a href="https://geekcodelab.com/wordpress-plugins/remove-woocommerce-product-content-pro/"  target="_blank" style="color:#46b450;font-weight: 600;">' . __( 'Premium Upgrade', 'remove-woocommerce-product-content' ) . '</a>'; 
	array_unshift( $links, $pro_link );	

	$settings_link = '<a href="'.admin_url('').'admin.php?page=rwpc-remove-woocommerce-product-content">' . __( 'Settings', 'remove-woocommerce-product-content' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;	
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'rwpc_plugin_add_settings_link');

// Register activation hook
register_activation_hook( __FILE__, 'rwpc_plugin_active_single_product_page' );
function rwpc_plugin_active_single_product_page() {
	if (is_plugin_active( 'remove-woocommerce-product-content-pro/remove-woocommerce-product-content-pro.php' ) ) {		
		deactivate_plugins('remove-woocommerce-product-content-pro/remove-woocommerce-product-content-pro.php');
   	}
}

/** Trigger an admin notice if WooCommerce is not installed.*/
if ( ! function_exists( 'rwpc_install_woocommerce_admin_notice' ) ) {
	function rwpc_install_woocommerce_admin_notice() { ?>
		<div class="error">
			<p>
				<?php
				// translators: %s is the plugin name.
				echo esc_html__( sprintf( '%s is enabled but not effective. It requires WooCommerce in order to work.', 'Remove Woocommerce Product Content' ), 'remove-woocommerce-product-content' );
				?>
			</p>
		</div>
		<?php
	}
}
function rwpc_woocommerce_constructor() {
    // Check WooCommerce installation
	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'rwpc_install_woocommerce_admin_notice' );
		return;
	}
}
add_action( 'plugins_loaded', 'rwpc_woocommerce_constructor' );

function rwpc_registerSettings() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_name = $plugin_data['Name'];
	register_setting( $plugin_name, 'rwpc_hide_single_wcpage_hook');
	register_setting( $plugin_name, 'rwpc_hide_product_wcpage_hook');
}

function rwpc_admin_option() {
    add_submenu_page(
		'woocommerce',
        'Remove Product Content',
		'Remove Product Content',
        'manage_options',
        'rwpc-remove-woocommerce-product-content',
		'rwpc_option_menu'
	);
}

function rwpc_option_menu()
{
	include( RWPC_PLUGIN_DIR_PATH . 'options.php');	
}

function rwpc_enqueue_styles($hook) {
	if($hook == 'woocommerce_page_rwpc-remove-woocommerce-product-content') {
		//STYLES
		wp_enqueue_style("rwpc-style.css",RWPC_PLUGIN_URL."/assets/css/rwpc-style.css",array(),RWPC_BUILD);

		//SCRIPTS
		wp_enqueue_script('jquery');	
		wp_enqueue_script("rwpc-script",RWPC_PLUGIN_URL."/assets/js/rwpc_script.js",array(),RWPC_BUILD);
	}
}

add_action('init', 'rwpc_customizing_single_product_hooks', 2  );

function rwpc_customizing_single_product_hooks() {	
	if(is_admin())	return;

	$rwpc_get_wc_single_opt=get_option('rwpc_hide_single_wcpage_hook');
	$rwpc_wc_hooks=json_decode($rwpc_get_wc_single_opt);

	$rwpc_get_wc_product_opt=get_option('rwpc_hide_product_wcpage_hook');
	$rwpc_wc_product_hooks=json_decode($rwpc_get_wc_product_opt);

	if(!empty($rwpc_wc_hooks)){		
		foreach($rwpc_wc_hooks as $rwpc_wc_key => $rwpc_wc_hook)
		{		
			if($rwpc_wc_hook  == "woocommerce_show_product_sale_flash"){
				remove_action( 'woocommerce_before_single_product_summary', $rwpc_wc_hook , 10);
			}
			if($rwpc_wc_hook  == "woocommerce_template_single_price"){				
				remove_action( 'woocommerce_single_product_summary', $rwpc_wc_hook , 10);				
			}
			if($rwpc_wc_hook  == "woocommerce_template_single_excerpt"){			
				remove_action( 'woocommerce_single_product_summary', $rwpc_wc_hook , 20);
			}
			if($rwpc_wc_hook  == "woocommerce_template_single_meta"){				
				remove_action( 'woocommerce_single_product_summary',$rwpc_wc_hook , 40);
			}
			if($rwpc_wc_hook  == "woocommerce_template_single_add_to_cart"){
				remove_action( 'woocommerce_single_product_summary',$rwpc_wc_hook , 30);
			}
			if($rwpc_wc_hook  == "woocommerce_show_product_thumbnails"){
				remove_action( 'woocommerce_product_thumbnails',$rwpc_wc_hook , 20);
			}
			if($rwpc_wc_hook  == "woocommerce_output_related_products"){
				remove_action( 'woocommerce_after_single_product_summary',$rwpc_wc_hook , 20);
				if ( ! function_exists( 'rwpc_hide_related_product_style' ) ) {
					function rwpc_hide_related_product_style(){ ?>
						<style>
							.related.products {
								display: none !important;
							}
						</style>
						<?php
					}
				}
				add_action( 'wp_head', 'rwpc_hide_related_product_style' );
			}
			if($rwpc_wc_hook  == "rwpc_woocommerce_product_description_tab"){				
				function rwpc_woocommerce_product_description_tabs( $tabs ) {
					unset( $tabs['description'] );
					return $tabs;
				}
				add_filter( 'woocommerce_product_tabs', 'rwpc_woocommerce_product_description_tabs', 98 );
			}
			if($rwpc_wc_hook  == "rwpc_woocommerce_product_review_tab"){
				function rwpc_woocommerce_product_review_tabs( $tabs ) {
					unset( $tabs['reviews'] );
					return $tabs;
				}
				add_filter( 'woocommerce_product_tabs', 'rwpc_woocommerce_product_review_tabs', 98 );
			}
			
			if($rwpc_wc_hook  == "rwpc_woocommerce_product_additional_information_tab"){				
				add_filter( 'woocommerce_product_tabs', 'rwpc_woocommerce_product_additional_info_tabs', 98 );
				function rwpc_woocommerce_product_additional_info_tabs( $tabs ) {
					unset( $tabs['additional_information'] );
					return $tabs;
				}				
			}
			if($rwpc_wc_hook  == "rwpc_woocommerce_product_all_tab"){				
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs',10);			
			}			
		}
	}
	
	if(!empty($rwpc_wc_product_hooks)) {		
		foreach($rwpc_wc_product_hooks as $rwpc_key => $rwpc_hook)
		{
			if($rwpc_hook  == "woocommerce_show_product_loop_sale_flash"){						
				remove_action( 'woocommerce_before_shop_loop_item_title', $rwpc_hook ,10);			
				remove_action( 'woocommerce_after_shop_loop_item_title', $rwpc_hook ,10);			
			}
			if($rwpc_hook  == "woocommerce_template_loop_add_to_cart"){				
				remove_action( 'woocommerce_after_shop_loop_item', $rwpc_hook , 10);			
			}
			if($rwpc_hook  == "rwpc_woocommerce_price"){			
				// remove price price
				add_filter( 'woocommerce_get_price_html', 'rwpc_remove_prices', 10, 2 );				 
				function rwpc_remove_prices( $price, $product ) {
					if(is_shop() || is_product_category())
					{
						$price = '';
					}
					return $price;
				}
			}
			if($rwpc_hook  == "rwpc_woocommerce_sale_price"){				
				// remove sale price
				add_filter( 'woocommerce_get_price_html', 'rwpc_change_sale_price', 10, 2 );
				function rwpc_change_sale_price( $price_html, $product ) {
					global $product;
					if(is_shop() || is_product_category())
					{
						if( $product->is_on_sale() ) {
							$price_html = '';
							return $price_html;
						}
					}
					return $price_html;
				}
			} 
			if($rwpc_hook  == "rwpc_woocommerce_variable_price"){				
				// remove variable price
				add_filter( 'woocommerce_variable_price_html', 'rwpc_remove_variable_prices', 10, 2 );
				function rwpc_remove_variable_prices( $price, $product ) {
					$price = '';
					return $price;
				}
			}
		}
	}
}

/**
 * Added HPOS support for woocommerce
 */
add_action( 'before_woocommerce_init', 'rwpc_before_woocommerce_init' );
function rwpc_before_woocommerce_init() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}