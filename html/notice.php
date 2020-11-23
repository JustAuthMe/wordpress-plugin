<?php defined('ABSPATH') || exit;
if (isset($notice_type)): ?>
<div id="jam-notice" class="jam-notice jam-notice-<?php echo $notice_type ?>">
    <?php echo $notice_message ?>
    <div class="jam-notice-dismiss" onclick="closeJamNotice()" title="<?php _e('Close') ?>">&times;</div>
</div>
<?php endif ?>
<script type="text/javascript">
    const closeJamNotice = () => {
        document.getElementById('jam-notice').style.display = 'none';
    };
</script>
