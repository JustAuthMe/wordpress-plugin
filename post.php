<?php

require_once '../../../wp-load.php';

if (!current_user_can('administrator')) {
    wp_safe_redirect(home_url());
    die;
}

if (empty($_POST['app_id']) || empty($_POST['secret'])) {
    wp_safe_redirect(admin_url('options-general.php?page=justauthme&manual_error'));
    die;
}

global $wpdb;
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

$sql = $wpdb->prepare("UPDATE " . JustAuthMe::get()->getSettingTableName() . " SET value=%s WHERE name='app_id'", $_POST['app_id']);
dbDelta($sql);

$sql = $wpdb->prepare("UPDATE " . JustAuthMe::get()->getSettingTableName() . " SET value=%s WHERE name='secret'", $_POST['secret']);
dbDelta($sql);

wp_safe_redirect(admin_url('options-general.php?page=justauthme&manual_success'));
die;
