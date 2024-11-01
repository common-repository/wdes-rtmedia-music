<?php

if ( ! defined( 'ABSPATH' ) ){ exit; }

global $wdes_music, $wp_query;

$max = wdes_music_nav_count();
$paged = $wp_query->query_vars['page'] ? : 1;
$paginations = wdes_music_pagination_links();

if( empty( $max ) || ( $max < 1 ) || ( $paged > $max ) || empty( $paginations ) ) :
	return;
endif;

do_action( 'wdes_music_before_pagination' );

/**
 * wdes_music_before_loop_pagination hook.
 * @hooked wdes_music_opening_div - 10 (outputs opening divs for the pagination)
 */

do_action( 'wdes_music_before_loop_pagination' );

?>

<?php if( 1 != $paged ) : ?>
	<a href="<?php wdes_music_previous_link( $paged, wdes_music_get_query() ); ?>"><?php _e( 'Previous', WDES_MUSIC ); ?></a>
<?php endif; ?>

<?php foreach( $paginations as $link ) : ?>
	<?php $current = $paged == $link ? 'current' : ''; ?>
	<?php if( is_numeric( $link ) ) : ?>
		<a href="<?php wdes_music_pagination_link( $link, wdes_music_get_query() ); ?>" class="<?php echo $current; ?>"><?php echo $link; ?></a>
  	<?php else : ?>
		<a>...</a> 
	<?php endif; ?>
<?php endforeach; ?>

<?php if( $max != $paged ) : ?>
	<a href="<?php wdes_music_next_link( $paged, wdes_music_get_query() ); ?>"><?php _e( 'Next', WDES_MUSIC ); ?></a>
<?php endif; ?>

<?php
/**
 * wdes_music_before_loop_pagination hook.
 * @hooked wdes_music_closing_div - 10 (outputs closing divs for the pagination)
 */

do_action( 'wdes_music_after_loop_pagination' );

do_action( 'wdes_music_after_search_form' );