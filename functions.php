<?php 
if(!defined('ABSPATH')) exit;

/**
 * Retrieves the option value for hiding single WooCommerce page content.
 *
 * This function retrieves the value of the 'rwpc_hide_single_wcpage_hook' option, which is used to determine
 * whether the content of single WooCommerce pages should be hidden or not.
 *
 * @since 1.0.0
 *
 * @return mixed The value of the 'rwpc_hide_single_wcpage_hook' option. If the option is not set, returns false.
 */
function get_option_rwpc_wc_single(){
    return get_option('rwpc_hide_single_wcpage_hook');
}

/**
 * Retrieves the option value for hiding product WooCommerce page content.
 *
 * This function retrieves the value of the 'rwpc_hide_product_wcpage_hook' option, which is used to determine
 * whether the content of single product WooCommerce pages should be hidden or not.
 *
 * @since 1.0.0
 *
 * @return mixed The value of the 'rwpc_hide_product_wcpage_hook' option. If the option is not set, returns false.
 */
function get_option_rwpc_wc_product(){
    return get_option('rwpc_hide_product_wcpage_hook');
}


/**
 * Displays a success message in the admin dashboard.
 *
 * This function generates a success message to be displayed in the admin dashboard.
 * The message is wrapped in HTML div tags with specific classes for styling.
 *
 * @since 1.0.0
 *
 * @param string $msg The success message to be displayed.
 *
 * @return string The success message wrapped in HTML div tags.
 */
function  success_option_msg_rwpc($msg){	
    return ' <div class="notice notice-success rwpc-success-msg is-dismissible"><p>'. $msg . '</p></div>';
}


/**
 * Displays an error message in the admin dashboard.
 *
 * This function generates an error message to be displayed in the admin dashboard.
 * The message is wrapped in HTML div tags with specific classes for styling.
 *
 * @since 1.0.0
 *
 * @param string $msg The error message to be displayed.
 *
 * @return string The error message wrapped in HTML div tags.
 */
function  failure_option_msg_rwpc($msg){
    return '<div class="notice notice-error rwpc-error-msg is-dismissible"><p>' . $msg . '</p></div>';
}


if ( ! function_exists( 'rwpc_install_woocommerce_admin_notice' ) ) {
	/**
	 * Displays an admin notice when WooCommerce is not active.
	*
	* This function is called when the plugin is activated and WooCommerce is not installed.
	* It displays an error message in the admin dashboard, informing the user that the plugin is enabled but not effective.
	*
	* @since 1.0.0
	*
	* @return void
	*/
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