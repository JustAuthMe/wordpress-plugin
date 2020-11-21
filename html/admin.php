<?php defined('ABSPATH') || exit; ?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()) ?></h1>
    <?php if (isset($_GET['reset_success'])): ?>
    <div id="message" class="notice notice-success is-dismissible">
        <p><?php _e('Plugin configuration successfully reset!', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?php _e('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['auto_success'])): ?>
    <div id="message" class="notice notice-success is-dismissible">
        <p><?php _e('All set up! Your users can now use JustAuthMe to log themselves into your website!', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?php _e('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['auto_error'])): ?>
    <div id="message" class="notice notice-error is-dismissible">
        <p><?php _e('Error occured during automated configuration. If this error persists, please contact support.', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?php _e('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['auto_error_setup'])): ?>
        <div id="message" class="notice notice-error is-dismissible">
            <p><?php _e('Automated configuration is not available: the plugin is already set up.', 'justauthme') ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e('Ignore this notification.') ?></span>
            </button>
        </div>
    <?php elseif (isset($_GET['manual_success'])): ?>
    <div id="message" class="notice notice-success is-dismissible">
        <p><?php _e('Configuration saved successfully!', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?php _e('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php elseif (isset($_GET['manual_error'])): ?>
    <div id="message" class="notice notice-error is-dismissible">
        <p><?php _e('Error occurred during configuration change.', 'justauthme') ?></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text"><?php _e('Ignore this notification.') ?></span>
        </button>
    </div>
    <?php endif ?>
    <div class="metabox-holder">
        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php _e('Automated configuration', 'justauthme') ?></h2>
            </div>
            <div class="inside">
                <?php if ($settings['app_id'] !== '' && $settings['secret'] !== ''): ?>
                <span style="color:grey"><?php _e('Automated configuration is not available: the plugin is already set up.', 'justauthme') ?></span>
                <br /><br />
                <a href="https://developers.justauth.me/dash" target="_blank" rel="noopener"><?php _e('Access to your JustAuthMe developer account', 'justauthme') ?></a>
                <br /><br /><br />
                <a href="<?php echo JAM_WEBROOT ?>reset.php" onclick="return confirm('<?php _e('Are you sure you want to erase this plugin configuration?', 'justauthme') ?>')">
                    <button class="button-secondary"><?php _e('Reset plugin', 'justauthme') ?></button>
                </a>
                <?php else: ?>
                    <a href="https://developers.justauth.me/dash/integration?url=<?php echo urlencode(home_url()) ?>" style="color:white;text-decoration:none">
                        <button style="border-radius:3px;border:none;background:#3498db;color:white;padding:10px 20px;cursor:pointer;">
                            <?php _e('Launch automated configuration', 'justauthme') ?>
                        </button>
                    </a>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="metabox-holder">
        <div style="height:250px" class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php _e('Manual configuration', 'justauthme') ?></h2>
            </div>
            <div class="inside">
                <form action="<?php echo JAM_WEBROOT ?>post.php" method="post">
                    <label for="app_id"><strong><?php _e('App ID:', 'justauthme') ?></strong></label><br />
                    <input required type="text" name="app_id" id="app_id" size="50" value="<?php echo $settings['app_id'] ?>" />
                    <br /><br />
                    <label for="secret"><strong><?php _e('Secret:', 'justauthme') ?></strong></label><br />
                    <input required type="password" name="secret" id="secret" size="50" value="<?php echo $settings['secret'] ?>" />
                    <a href="javascript:void(0)" onclick="showHide()" id="showhide"><?php _e('Show', 'justauthme') ?></a>
                    <br /><br />
                    <input class="button-primary" type="submit" />
                </form>
            </div>
        </div>
    </div>
    <span style="color:grey">
        <?php echo esc_html(get_admin_page_title()) . ' v' . JustAuthMe::get()->getPluginVersion() ?>
    </span>
</div>
<script type="text/javascript">
    const secret = document.getElementById('secret');
    const showhide = document.getElementById('showhide');
    const showHide = () => {
        if (secret.getAttribute('type') === 'password') {
            secret.setAttribute('type', 'text');
            showhide.innerText = '<?php _e('Hide', 'justauthme') ?>';
        } else {
            secret.setAttribute('type', 'password');
            showhide.innerText = '<?php _e('Show', 'justauthme') ?>';
        }
    }
</script>
