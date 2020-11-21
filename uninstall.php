<?php
defined('WP_UNINSTALL_PLUGIN ') || exit;

global $wpdb;
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

$sql = "DROP TABLE IF EXISTS `" . $this->user_table_name . "`;";
$sql2 = "DROP TABLE IF EXISTS `" . $this->setting_table_name . "`;";
dbDelta($sql);
dbDelta($sql2);
