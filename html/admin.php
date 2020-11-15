<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()) ?></h1>
    <?php if (isset($_GET['reset_success'])): ?>
    <div id="message" class="notice notice-success is-dismissible">
        <p><?= __('Plugin configuration successfully reset!', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?= __('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['auto_success'])): ?>
    <div id="message" class="notice notice-success is-dismissible">
        <p><?= __('All set up! Your users can now use JustAuthMe to log themselves into your website!', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?= __('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['auto_error'])): ?>
    <div id="message" class="notice notice-error is-dismissible">
        <p><?= __('Error occured during automated configuration. If this error persists, please contact support.', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?= __('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['auto_error_setup'])): ?>
        <div id="message" class="notice notice-error is-dismissible">
            <p><?= __('Automated configuration is not available: the plugin is already set up.', 'justauthme') ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?= __('Ignore this notification.') ?></span>
            </button>
        </div>
    <?php elseif (isset($_GET['manual_success'])): ?>
    <div id="message" class="notice notice-success is-dismissible">
        <p><?= __('Configuration saved successfully!', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?= __('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['manual_error'])): ?>
    <div id="message" class="notice notice-error is-dismissible">
        <p><?= __('Error occurred during configuration change.', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?= __('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php endif ?>
    <div class="stuffbox">
        <div class="inside">
            <h2><?= __('Automated configuration', 'justauthme') ?></h2>
            <?php if ($settings['app_id'] !== '' && $settings['secret'] !== ''): ?>
            <span style="color:grey"><?= __('Automated configuration is not available: the plugin is already set up.', 'justauthme') ?></span>
            <br /><br />
            <a href="https://developers.justauth.me/dash" target="_blank" rel="noopener"><?= __('Access to your JustAuthMe developer account', 'justauthme') ?></a>
            <br /><br /><br />
            <a href="<?= JAM_WEBROOT ?>reset.php" onclick="return confirm('<?= __('Are you sure you want to erase this plugin configuration?', 'justauthme') ?>')">
                <button class="button-secondary"><?= __('Reset plugin', 'justauthme') ?></button>
            </a>
            <?php else: ?>
                <a href="https://developers.justauth.me/dash/integration?url=<?= urlencode(home_url()) ?>" style="color:white;text-decoration:none">
                    <button style="border-radius:3px;border:none;background:#3498db;color:white;padding:10px 20px;cursor:pointer;">
                        <?= __('Launch automated configuration', 'justauthme') ?>
                    </button>
                </a>
            <?php endif ?>
        </div>
    </div>
    <div style="height:250px" class="stuffbox">
        <div class="inside">
            <h2><?= __('Manual configuration', 'justauthme') ?></h2>

            <form action="<?= JAM_WEBROOT ?>post.php" method="post">
                <label for="app_id"><strong><?= __('App ID:', 'justauthme') ?></strong></label><br />
                <input required type="text" name="app_id" id="app_id" size="50" value="<?= $settings['app_id'] ?>" />
                <br /><br />
                <label for="secret"><strong><?= __('Secret:', 'justauthme') ?></strong></label><br />
                <input required type="password" name="secret" id="secret" size="50" value="<?= $settings['secret'] ?>" />
                <a href="javascript:void(0)" onclick="showHide()" id="showhide"><?= __('Show', 'justauthme') ?></a>
                <br /><br />
                <input class="button-primary" type="submit" />
            </form>
        </div>
    </div>
    <span style="color:grey">
        <?= esc_html(get_admin_page_title()) . ' v' . trim(file_get_contents(JAM_PLUGIN_DIR . 'version.txt')) ?>
    </span>
</div>
<script type="text/javascript">
    const secret = document.getElementById('secret');
    const showhide = document.getElementById('showhide');
    const showHide = () => {
        if (secret.getAttribute('type') === 'password') {
            secret.setAttribute('type', 'text');
            showhide.innerText = '<?= __('Hide', 'justauthme') ?>';
        } else {
            secret.setAttribute('type', 'password');
            showhide.innerText = '<?= __('Show', 'justauthme') ?>';
        }
    }
</script>
