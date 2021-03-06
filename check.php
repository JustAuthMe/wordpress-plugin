<?php
require_once '../../../wp-load.php';

header('Content-Type: application/json');

echo json_encode([
    'install_type' => defined('WC_VERSION') ? 'woocommerce' : 'wordpress',
    'version' => defined('WC_VERSION') ? WC_VERSION : $wp_version,
    'plugin_version' => JustAuthMe::get()->getPluginVersion(),
    'name' => get_bloginfo('name'),
    'icon' => get_site_icon_url(256)
]);
