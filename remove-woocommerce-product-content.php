<?php
/*
Plugin Name: Remove Woocommerce Product Content
Description: Hide product content from  woocommerce Single product page, Shop page and category page
Author: Geek Code Lab
Version: 2.3.0
WC tested up to: 8.6.1
Author URI: https://geekcodelab.com/
Text Domain: remove-woocommerce-product-content
*/
if(!defined('ABSPATH')) exit;

define("RWPC_BUILD","2.3.0");

if(!defined("RWPC_PLUGIN_DIR_PATH")) define("RWPC_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
	
if(!defined("RWPC_PLUGIN_URL")) define("RWPC_PLUGIN_URL", plugins_url().'/'.basename(dirname(__FILE__)));

if (!defined("RWPC_PLUGIN_BASENAME")) define("RWPC_PLUGIN_BASENAME", plugin_basename(__FILE__));

if (!defined("RWPC_PLUGIN_DIR")) define("RWPC_PLUGIN_DIR", plugin_basename(__DIR__));

require_once( RWPC_PLUGIN_DIR_PATH .'functions.php');
require(RWPC_PLUGIN_DIR_PATH . 'updater/updater.php');

add_action('upgrader_process_complete', 'rwpc_updater_activate'); // remove  transient  on plugin  update

/**
 * Adds links to the plugin's settings page and support resources in the plugin action links.
 *
 * This function is hooked to the 'plugin_action_links_$plugin' filter and adds links to the plugin's settings page,
 * support resources, and a premium upgrade link. The links are added to the plugin's action links in the WordPress admin area.
 *
 * @since 1.0.0
 *
 * @param array $links An array of plugin action links.
 *
 * @return array The modified array of plugin action links.
 */
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'rwpc_plugin_add_settings_link');
function rwpc_plugin_add_settings_link( $links ) { 
    $support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __( 'Support', 'remove-woocommerce-product-content' ) . '</a>';
    array_unshift( $links, $support_link );

    $pro_link = '<a href="https://geekcodelab.com/wordpress-plugins/remove-woocommerce-product-content-pro/"  target="_blank" style="color:#46b450;font-weight: 600;">' . __( 'Premium Upgrade', 'remove-woocommerce-product-content' ) . '</a>'; 
    array_unshift( $links, $pro_link );

    $settings_link = '<a href="'.admin_url('').'admin.php?page=rwpc-remove-woocommerce-product-content">' . __( 'Settings', 'remove-woocommerce-product-content' ) . '</a>';
    array_unshift( $links, $settings_link );

    return $links;
}


/**
 * Activates the Remove Woocommerce Product Content plugin and deactivates the premium version if it's active.
 *
 * This function is hooked to the 'activation_hook' for the plugin file. It calls the updater activation function,
 * checks if the premium version of the plugin is active, and deactivates it if it is.
 *
 * @since 1.0.0
 *
 * @return void
 */
register_activation_hook( __FILE__, 'rwpc_plugin_active_single_product_page' );
function rwpc_plugin_active_single_product_page() {
    rwpc_updater_activate();
    if (is_plugin_active( 'remove-woocommerce-product-content-pro/remove-woocommerce-product-content-pro.php' ) ) {		
        deactivate_plugins('remove-woocommerce-product-content-pro/remove-woocommerce-product-content-pro.php');
       }
}

/**
 * Initializes the Remove Woocommerce Product Content plugin and checks for WooCommerce installation.
 *
 * This function is hooked to the 'plugins_loaded' action and checks if WooCommerce is installed.
 * If WooCommerce is not installed, it adds an admin notice to prompt the user to install WooCommerce.
 *
 * @since 1.0.0
 *
 * @return void
 */
add_action( 'plugins_loaded', 'rwpc_woocommerce_constructor' );
function rwpc_woocommerce_constructor() {
    // Check WooCommerce installation
    if ( ! function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'rwpc_install_woocommerce_admin_notice' );
        return;
    }
}


/**
 * Registers plugin settings for hiding WooCommerce hooks on single product and product category pages.
 *
 * This function retrieves the plugin's name from the plugin data and registers two settings:
 * - rwpc_hide_single_wcpage_hook: Stores the selected hooks to hide on the single product page.
 * - rwpc_hide_product_wcpage_hook: Stores the selected hooks to hide on the product category page.
 *
 * @since 1.0.0
 *
 * @return void
 */
add_action('admin_init', 'rwpc_registerSettings');
function rwpc_registerSettings() {
    $plugin_data = get_plugin_data( __FILE__ );
    $plugin_name = $plugin_data['Name'];
    register_setting( $plugin_name, 'rwpc_hide_single_wcpage_hook');
    register_setting( $plugin_name, 'rwpc_hide_product_wcpage_hook');
}


/**
 * Adds a submenu page under the WooCommerce menu in the WordPress admin area.
 * This page is used to manage the plugin's settings and options.
 *
 * @since 1.0.0
 *
 * @return void
 */
add_action('admin_menu', 'rwpc_admin_option' );
function rwpc_admin_option() {
    add_submenu_page(
        'woocommerce', // The parent menu slug.
        'Remove Product Content', // The page title.
        'Remove Product Content', // The menu title.
        'manage_options', // The capability required to access this menu.
        'rwpc-remove-woocommerce-product-content', // The menu slug.
        'rwpc_option_menu' // The function to call to render the page content.
    );
}


/**
 * This function is used to render the plugin's settings and options page in the WordPress admin area.
 * It includes the 'options.php' file from the plugin's directory.
 *
 * @since 1.0.0
 *
 * @return void
 */
function rwpc_option_menu(){
    include( RWPC_PLUGIN_DIR_PATH . 'options.php');
}


/**
 * Enqueues styles and scripts for the Remove Woocommerce Product Content plugin.
 *
 * This function is hooked to the 'admin_enqueue_scripts' action and is responsible for
 * enqueuing the necessary CSS and JavaScript files for the plugin's settings page.
 *
 * @since 1.0.0
 *
 * @param string $hook The current admin page hook.
 *
 * @return void
 */
add_action('admin_enqueue_scripts','rwpc_enqueue_styles');
function rwpc_enqueue_styles($hook) {
    if($hook == 'woocommerce_page_rwpc-remove-woocommerce-product-content') {
        //STYLES
        wp_enqueue_style("rwpc-style.css",RWPC_PLUGIN_URL."/assets/css/rwpc-style.css",array(),RWPC_BUILD);

        //SCRIPTS
        wp_enqueue_script('jquery');	
        wp_enqueue_script("rwpc-script",RWPC_PLUGIN_URL."/assets/js/rwpc_script.js",array(),RWPC_BUILD);
    }
}

/**
 * Customizes the single product and product category pages in WooCommerce.
 * Removes specified hooks and actions related to WooCommerce hooks.
 *
 * @return void
 */
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
				remove_action( 'woocommerce_after_shop_loop_item_title', $rwpc_hook ,6);			
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
 * 
 * This function is used to declare compatibility with the custom order tables feature in WooCommerce.
 * It checks if the required class exists and then calls the declare_compatibility method from the FeaturesUtil class.
 *
 * @return void
 */
add_action( 'before_woocommerce_init', 'rwpc_before_woocommerce_init' );
function rwpc_before_woocommerce_init() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
}