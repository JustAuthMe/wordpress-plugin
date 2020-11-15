<?php

require_once '../../../wp-load.php';

if (!current_user_can('administrator')) {
    wp_safe_redirect(home_url());
    die;
}

global $wpdb;
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

$sql = "UPDATE " . JustAuthMe::get()->getSettingTableName() . " SET value='' WHERE name='app_id'";
dbDelta($sql);
$sql = "UPDATE " . JustAuthMe::get()->getSettingTableName() . " SET value='' WHERE name='secret'";
dbDelta($sql);

wp_safe_redirect(admin_url('options-general.php?page=justauthme&reset_success'));
