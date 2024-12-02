<?php

if (!defined('ABSPATH')) exit;

/**
 * License manager module
 */
function rwpc_updater_utility() {
    $prefix = 'RWPC_';
    $settings = [
        'prefix' => $prefix,
        'get_base' => RWPC_PLUGIN_BASENAME,
        'get_slug' => RWPC_PLUGIN_DIR,
        'get_version' => RWPC_BUILD,
        'get_api' => 'https://download.geekcodelab.com/',
        'license_update_class' => $prefix . 'Update_Checker'
    ];

    return $settings;
}

function rwpc_updater_activate() {

    // Refresh transients
    delete_site_transient('update_plugins');
    delete_transient('rwpc_plugin_updates');
    delete_transient('rwpc_plugin_auto_updates');
}

require_once(RWPC_PLUGIN_DIR_PATH . 'updater/class-update-checker.php');
