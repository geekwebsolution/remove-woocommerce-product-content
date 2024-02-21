<?php 
if(!defined('ABSPATH')) exit;

// Get single page option
function get_option_rwpc_wc_single()
{
	return get_option('rwpc_hide_single_wcpage_hook');
}
// Get product page option
function get_option_rwpc_wc_product()
{
	return get_option('rwpc_hide_product_wcpage_hook');
}

// Success message
function  success_option_msg_rwpc($msg)
{	
	return ' <div class="notice notice-success rwpc-success-msg is-dismissible"><p>'. $msg . '</p></div>';		
}

// Error message
function  failure_option_msg_rwpc($msg)
{
	return '<div class="notice notice-error rwpc-error-msg is-dismissible"><p>' . $msg . '</p></div>';		
}
?>