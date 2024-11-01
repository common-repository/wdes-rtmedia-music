<?php

if ( !defined('ABSPATH') ){ exit; }

function wdes_music_all(){
	global $wdes_music, $bp;
	$pages = array_map( function( $data ){ return $data['slug']; }, $wdes_music->names );
	if( in_array( $bp->current_component, $pages ) ){
		add_action( 'bp_template_content', 'wdes_music_all_template' );
	}
	bp_core_load_template( 'bp_template_content' );
}

function wdes_music_all_template(){
	wdes_music_include_template( 'all' );
}

function wdes_music_music_shortcode_template(){
	if( wdes_music_option( 'wdes_music_all_music_shortcode_by' ) ){
		add_filter( "wdes_music_displayed_user_id", function(){ return wdes_music_option( 'wdes_music_all_music_shortcode_by' ); });
	}
	ob_start();
	wdes_music_include_template( 'shortcodes/music' );
	return ob_get_clean();
}

function wdes_music_pagiantion(){
	wdes_music_include_template( 'pagination', null, 'pagination' );
}

function wdes_music_tooltip( $text ){
	?>
    <span class="rtm-tooltip">
   	 	<i class="dashicons dashicons-info rtmicon"></i>
        <span class="rtm-tip"><?php _e( $text ); ?></span>
 	</span>
	<?php
}

function wdes_music_search_form(){
	wdes_music_include_template( 'search-form' );
}

function wdes_music_opening_div(){
	echo '<div class="wdes-music-clear"></div>';
	echo '<div class="wdes-music-paged">';
}

function wdes_music_closing_div(){
	echo '</div>';
}

function wdes_music_audio_html( $id, $size = 'full', $echo = true ){
	global $wdes_music;
 	$ti_height = 140;
	if( function_exists( 'wdes_music_featured_image' ) && wdes_music_option( 'wdes_music_thumbnail_image_height' ) ) {
		$ti_height = wdes_music_option( 'wdes_music_thumbnail_image_height' );
	}
	$link = wp_get_attachment_url( wdes_music_get_media( 'media_id', $id ) );
	$media_id = wdes_music_get_media( 'media_id', $id );
	$img_url = wdes_music_get_image_url( $id, $size );
	$bg = $img_url ? "background-image:url('{$img_url}');" : '';
	$audio = sprintf(
		'<div class="wdes-music-audio link" style="%s"><audio src="%s" width="%s" height="%s" type="audio/mp3" class="wp-audio-shortcode" id="bp_media_audio_%s" controls="controls" preload="none" style="opacity:0;"></audio></div>', $bg, $link, '100%', $ti_height, $media_id
	 );
	if( ! $echo ){ return $audio; }
	echo $audio;
}

function wdes_music_heading( $text ){
	?><div class="wdes-music-title"><?php echo $text; ?></div><?php
}