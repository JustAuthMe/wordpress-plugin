<?php defined('ABSPATH') || exit; ?>
<a href="https://core.justauth.me/auth?app_id=<?php echo $settings['app_id'] ?>&redirect_url=<?php echo JAM_WEBROOT ?>callback.php" class="jam-button" style="margin-bottom:1rem">
    <div class="jam-btn-content">
        <div class="jam-btn-logo">
            <img src="https://static.justauth.me/medias/2_WHITE.png" />
        </div>
        <div class="jam-btn-text">
            <?php _e('Login with', 'justauthme') ?>
            <span class="jam-btn-brand">
                <span class="jam-btn-bold">JustAuth</span><span class="jam-btn-thin">Me</span>
            </span>
        </div>
    </div>
</a>
