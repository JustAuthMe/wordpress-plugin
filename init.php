<?php

require_once '../../../wp-load.php';

if (!current_user_can('administrator')) {
    wp_safe_redirect(home_url());
    die;
}

if (empty($_GET['app_id']) || empty($_GET['secret'])) {
    wp_safe_redirect(admin_url('options-general.php?page=justauthme&auto_error'));
    die;
}

$settings = JustAuthMe::get()->fetchSettings();
if ($settings['app_id'] !== '' && $settings['secret'] !== '') {
    wp_safe_redirect(admin_url('options-general.php?page=justauthme&auto_error_setup'));
    die;
}

global $wpdb;
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

$sql = $wpdb->prepare("UPDATE " . JustAuthMe::get()->getSettingTableName() . " SET value=%s WHERE name='app_id'", $_GET['app_id']);
dbDelta($sql);
$sql = $wpdb->prepare("UPDATE " . JustAuthMe::get()->getSettingTableName() . " SET value=%s WHERE name='secret'", $_GET['secret']);
dbDelta($sql);

wp_safe_redirect(admin_url('options-general.php?page=justauthme&auto_success'));
