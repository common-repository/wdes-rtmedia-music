<?php

add_action( 'wdes_music_actions', 'wdes_music_actions', 5 );
function wdes_music_actions(){
	add_action( 'wp_head', 'wdes_music_wp_head' );
	add_filter( 'rtmedia_add_settings_sub_tabs', 'wdes_music_tab' );	
	add_filter( 'wdes_music_template_filename', 'wdes_music_upload_music' );
	add_action( 'wp_enqueue_scripts', 'wdes_music_enqueue' );
	add_action( 'admin_print_styles', 'wdes_music_admin_styles' );
	add_action( 'admin_print_scripts', 'wdes_music_admin_scripts' );
	add_action( 'bp_setup_nav', 'wdes_music_add_new_nav_item', 100 );
	add_action( 'wp_ajax_wdes_music_aisf', 'wdes_music_ajax_in_search_field' );
	add_action( 'wp_ajax_nopriv_wdes_music_aisf', 'wdes_music_ajax_in_search_field' );
	add_shortcode( 'wdes-rtmedia-music', 'wdes_music_music_shortcode_template' );
	add_action( 'wdes_music_before_loop_pagination', 'wdes_music_opening_div', 10 );
	add_action( 'wdes_music_after_loop_pagination', 'wdes_music_closing_div', 10 );
}