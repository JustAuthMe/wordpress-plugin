<?php

require_once '../../../wp-load.php';

if (!isset($_GET['access_token'])) {
    wp_safe_redirect(home_url('?jam_no_token'));
    die;
}

require_once JAM_PLUGIN_DIR . 'class/apicall.class.php';

$settings = JustAuthMe::get()->fetchSettings();

$apiCall = new ApiCall();
$apiCall->setUrl('https://core.justauth.me/api/data?access_token=' . $_GET['access_token'] . '&secret=' . $settings['secret'])
    ->exec();

$obj = $apiCall->responseObj();

global $wpdb;
$sql = $wpdb->prepare("SELECT COUNT(*) AS cnt FROM " . JustAuthMe::get()->getUserTableName() . " WHERE jam_id = %s", $obj->jam_id);
$cnt = (int) $wpdb->get_results($sql)[0]->cnt;

if ($cnt > 0) {
    $sql = $wpdb->prepare("SELECT user_id FROM " . JustAuthMe::get()->getUserTableName() . " WHERE jam_id = %s", $obj->jam_id);
    $uid = (int) $wpdb->get_results($sql)[0]->user_id;

    JustAuthMe::get()->login($uid);
} else {
    if (isset($obj->email)) {
        $user = get_user_by_email($obj->email);
        if ($user !== false) {
            $sql = $wpdb->prepare("INSERT INTO " . JustAuthMe::get()->getUserTableName() . " (user_id, jam_id) VALUES(%d, %s)", [$user->ID, $obj->jam_id]);
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);

            JustAuthMe::get()->login($user->ID);
        } elseif (get_option('users_can_register')) {
            $email_local = explode('@', $obj->email)[0];
            $i = '';
            do {
                $username = $email_local . $i;
                $i++;
            } while (get_user_by('slug', $username) !== false);

            $userdata = [
                'user_login' => $username,
                'user_email' => $obj->email,
                'user_pass' => wp_generate_password(32, true, true),
            ];

            if (isset($obj->firstname)) {
                $userdata['first_name'] = $obj->firstname;
            }

            if (isset($obj->lastname)) {
                $userdata['last_name'] = $obj->lastname;
            }

            $uid = wp_insert_user($userdata);

            $sql = $wpdb->prepare("INSERT INTO " . JustAuthMe::get()->getUserTableName() . " (user_id, jam_id) VALUES(%d, %s)", [$uid, $obj->jam_id]);
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);

            JustAuthMe::get()->login($uid);
        } else {
            wp_safe_redirect(home_url('jam_cant_register'));
            die;
        }
    } else {
        wp_safe_redirect(home_url('jam_no_email'));
        die;
    }
}
