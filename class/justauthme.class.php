<?php

defined('ABSPATH') || exit;

class JustAuthMe {
    const USER_TABLE_NAME = 'justauthme_user';
    const SETTING_TABLE_NAME = 'justauthme_setting';

    private static $instance = null;

    private $user_table_name;
    private $setting_table_name;

    public function __construct() {
        global $wpdb;
        $this->user_table_name = $wpdb->prefix . self::USER_TABLE_NAME;
        $this->setting_table_name = $wpdb->prefix . self::SETTING_TABLE_NAME;

        $this->registerHooks();
        $this->setActions();
        $this->setFilters();
    }

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getUserTableName() {
        return $this->user_table_name;
    }

    public function getSettingTableName() {
        return $this->setting_table_name;
    }

    private function registerHooks() {
        register_activation_hook(JAM_PLUGIN_FILE, [$this, 'activateHook']);
        register_deactivation_hook(JAM_PLUGIN_FILE, [$this, 'deactivateHook']);
        register_uninstall_hook(JAM_PLUGIN_FILE, [$this, 'uninstallHook']);
    }

    private function setActions() {
        add_action('wp_body_open', [$this, 'handleNotice']);

        add_action('plugins_loaded', [$this, 'loadTranslations']);

        add_action('admin_menu', [$this, 'setupAdminMenu']);

        add_action('wp_head', [$this, 'includeCSS']);
        add_action('login_head', [$this, 'includeCSS']);

        add_action('bp_before_account_details_fields', [$this, 'displayButton']);
        add_action('bp_before_sidebar_login_form', [$this, 'displayButton']);

        add_action('comment_form_top', [$this, 'displayButton']);
        add_action('comment_form_must_log_in_after', [$this, 'displayButton']);

        add_action('login_form', [$this, 'displayButton']);
        add_action('register_form', [$this, 'displayButton']);
        add_action('after_signup_form', [$this, 'displayButton']);

        add_action('bp_before_account_details_fields', [$this, 'displayButton']);
        add_action('bp_before_sidebar_login_form', [$this, 'displayButton']);

    }

    private function setFilters() {
        add_filter('plugin_action_links', [$this, 'addSettingsLink'], 10, 2);
    }

    public function setupAdminMenu() {
        add_options_page(
            'JustAuthMe',
            'JustAuthMe',
            'manage_options',
            'justauthme',
            [$this, 'displayAdminPage']
        );
    }

    public function activateHook() {
        $sql = "CREATE TABLE IF NOT EXISTS `" . $this->user_table_name . "` (
            `id` int NOT NULL AUTO_INCREMENT,
            `user_id` int NOT NULL,
            `jam_id` varchar(255) NOT NULL,
            `linked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $sql2 = "CREATE TABLE IF NOT EXISTS `" . $this->setting_table_name . "` (
            `id` int NOT NULL AUTO_INCREMENT,
            `name` varchar(255) CHARACTER SET utf8 NOT NULL,
            `value` text NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $sql3 = "INSERT INTO `" . $this->setting_table_name . "` (name, value) VALUES
            ('app_id', ''),
            ('secret', '');";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        dbDelta($sql2);

        if (empty($this->fetchSettings())) {
            dbDelta($sql3);
        }
    }

    public function deactivateHook() {}

    public function uninstallHook() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "DROP TABLE IF EXISTS `" . $this->user_table_name . "`;";
        $sql2 = "DROP TABLE IF EXISTS `" . $this->setting_table_name . "`;";
        dbDelta($sql);
        dbDelta($sql2);
    }

    public function includeCSS() {
        require_once JAM_PLUGIN_DIR . 'html/css.php';
    }

    public function displayButton() {
        $user = wp_get_current_user();
        if ($user === null || $user->ID === 0) {
            $settings = $this->fetchSettings();
            require JAM_PLUGIN_DIR . 'html/button.php';
        }
    }

    public function displayAdminPage() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = $this->fetchSettings();
        require JAM_PLUGIN_DIR . 'html/admin.php';
    }

    public function addSettingsLink($links, $file) {
        $plugin = plugin_basename(JAM_PLUGIN_FILE);

        if($file == $plugin) {
            $settings_link = '<a href="options-general.php?page=justauthme">' . __( "Settings" ) . '</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    public function fetchSettings() {
        global $wpdb;
        $data = $wpdb->get_results("SELECT * FROM " . $this->setting_table_name);

        $settings = [];
        foreach ($data as $d) {
            $settings[$d->name] = $d->value;
        }

        return $settings;
    }

    public function login($user_id, $redirect_to = '') {
        wp_clear_auth_cookie();
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);

        $redirect_to = $redirect_to === '' ? home_url('?jam_success') : $redirect_to;
        wp_safe_redirect($redirect_to);
        die;
    }

    public function loadTranslations() {
        load_plugin_textdomain('justauthme', FALSE, basename(dirname(JAM_PLUGIN_FILE)) . '/lang/');
    }

    public function handleNotice() {
        if (isset($_GET['jam_no_token'])) {
            $notice_type = 'error';
            $notice_message = __('Error: no token was received. If this problem persists, please contact JustAuthMe support.', 'justauthme');
        } elseif (isset($_GET['jam_no_email'])) {
            $notice_type = 'error';
            $notice_message = __('Error: no personal info was received. Please remove this website from your JustAuthMe app and try again.', 'justauthme');
        } elseif (isset($_GET['jam_cant_register'])) {
            $notice_type = 'error';
            $notice_message = __('Error: users are not allowed to register on this website.', 'justauthme');
        } elseif (isset($_GET['jam_success'])) {
            $notice_type = 'success';
            $notice_message = __('Logged in successfully! Welcome back!', 'justauthme');
        }

        require_once JAM_PLUGIN_DIR . 'html/notice.php';
    }
}
