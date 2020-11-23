<?php
/**
 * JustAuthMe
 *
 * @package PluginPackage
 * @author Peter Cauty
 * @copyright 2020 JustAuthMe
 * @license MIT
 *
 * @wordpress-plugin
 * Plugin Name: JustAuthMe
 * Plugin URI: https://developers.justauth.me/
 * Description: Wordpress plugin for JustAuthMe
 * Version: 1.0.3
 * Author: Peter Cauty
 * Text Domain: justauthme
 * Domain path: /lang
 * License: MIT
 */

defined('ABSPATH') || exit;

if (!defined('JAM_PLUGIN_FILE')) {
    define('JAM_PLUGIN_FILE', __FILE__);
}

if (!defined('JAM_PLUGIN_DIR')) {
    define('JAM_PLUGIN_DIR', __DIR__ . '/');
}

if (!defined('WP_WEBROOT')) {
    define('JAM_WEBROOT', home_url('wp-content/plugins/justauthme/'));
}

require_once __DIR__ . '/class/justauthme.class.php';

JustAuthMe::get();
