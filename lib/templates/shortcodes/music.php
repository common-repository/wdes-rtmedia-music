<?php

if ( ! defined( 'ABSPATH' ) ){ exit; }

global $wdes_music, $wp_query;

?>
<div id="wdes-music">
    <?php wdes_music_search_form(); ?>
    <div class="wdes-music-group">
        <?php if( wdes_music_ids() ) : ?>
        	<?php foreach( wdes_music_ids() as $id ){ ?>
            	<div class="wdes-music-item">
                	<?php wdes_music_audio_html( $id, 'wdes_music_thumbnail_image' ); ?>
                    <a href="<?php rtmedia_permalink( wdes_music_get_media( 'media_id', $id ) ); ?>"><strong><?php echo wdes_music_title( $id ); ?></strong></a>
				</div>
			<?php } ?>
		<?php else : ?>
            <p><?php _e( 'Nothing Found', WDES_MUSIC ); ?></p>
        <?php endif; ?>
		<?php wdes_music_pagiantion(); ?>
	</div>
</div>