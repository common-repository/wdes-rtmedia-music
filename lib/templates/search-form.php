<?php

if ( ! defined( 'ABSPATH' ) ){ exit; }

global $wdes_genre;

$search = wdes_music_get( 'search' );
$order = wdes_music_get( 'order' );
$orderby = wdes_music_get( 'orderby' );
$duration = wdes_music_get( 'duration' );

do_action( 'wdes_music_before_search_form' );

?>
<form id="wdes-music-search" action="<?php echo wdes_music_url(); ?>" method="get">
    <div class="wdes-music-inline">
		<?php do_action( 'wdes_music_before_search' ); ?>
        <div class="wdes-music-inline wdes-music-relative"><input type="text" name="search" id="wdes-music-1" class="wdes-gs-field search wdes-music-ajax" value="<?php echo $search; ?>" placeholder="<?php _e( 'Search Music', WDES_MUSIC ); ?>" autocomplete="off" /></div>
		<?php do_action( 'wdes_music_search_form_add_search_fields' ); ?>
        <?php do_action( 'wdes_music_search_form_before_submit' ); ?>
        <input class="button wdes-gs-field" type="submit" value="<?php _e( 'Search', WDES_MUSIC ); ?>" />
        <div class="wdes-music-advance" style="display:none;">
        	<div class="wdes-music-group wdes-music-inline">
            	<div class="wdes-music-inline wdes-music-relative"><input type="text" name="duration" id="wdes-music-2" class="wdes-gs-field duration wdes-music-ajax" value="<?php echo $duration; ?>" placeholder="<?php _e( 'Duration Time' ); ?>" autocomplete="off" /></div>
				<?php do_action( 'wdes_music_search_form_before_orderby' ); ?>
                <select name="orderby" class="wdes-gs-field orderby wdes-music-ajax">
                    <option value=""<?php selected( '', $orderby ); ?>><?php _e( 'Order By', WDES_MUSIC ); ?></option>
                    <option value="media_title"<?php selected( 'media_title', $orderby ); ?>><?php _e( 'Title', WDES_MUSIC ); ?></option>
                    <option value="upload_date"<?php selected( 'upload_date', $orderby ); ?>><?php _e( 'Date', WDES_MUSIC ); ?></option>
                </select>
                <?php do_action( 'wdes_music_search_form_before_order' ); ?>
                <select name="order" class="wdes-gs-field order wdes-music-ajax">
                    <option value=""<?php selected( '', $order ); ?>><?php _e( 'Order', WDES_MUSIC ); ?></option>
                    <option value="ASC"<?php selected( 'ASC', $order ); ?>><?php _e( 'ASC', WDES_MUSIC ); ?></option>
                    <option value="DESC"<?php selected( 'DESC', $order ); ?>><?php _e( 'DESC', WDES_MUSIC ); ?></option>
                </select>
            </div>
        </div>
        <p class="wdes-music-alignright wdes-music-toggle"><span><?php _e( 'Advance Search', WDES_MUSIC ); ?></span></p>
    </div>
</form>
<?php do_action( 'wdes_music_after_search_form' ); ?>