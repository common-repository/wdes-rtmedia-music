<?php

if ( ! defined( 'ABSPATH' ) ){ exit; }

global $bp, $wdes_music;

$domain_user_id = $bp->displayed_user->id;
$loggedin_user_id = $bp->loggedin_user->id;

?>
<div id="wdes-music">
    <?php wdes_music_heading( $wdes_music->upload_music->name ); ?>
	<div class="wdes-music-border"></div>
	<p><?php _e( 'Please select audio files only for better results.', WDES_MUSIC ); ?></p>
    <?php if( $domain_user_id == $loggedin_user_id ) : ?>
        <div class="wdes-music-upload">
			<?php echo do_shortcode ( '[rtmedia_uploader media_type="music" media_author="' . $domain_user_id . '"]' ); ?>
     	</div>
        <div class="wdes-music-cancel-start-btn" style="display:none;">
        	<span class="button cancel-btn"><?php _e( 'Cancel', WDES_MUSIC ); ?></span>
            <span class="button start-upload-btn"><?php _e( 'Start upload', WDES_MUSIC ); ?></span>
        </div>
    <?php endif; ?>
</div>