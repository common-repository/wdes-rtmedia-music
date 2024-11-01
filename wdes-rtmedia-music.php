<?php

/**
 * Plugin Name: WDES rtMedia Music
 * Plugin URI: https://www.anthonycarbon.com/
 * Description: WDES rtMedia Music is designed for rtMedia WordPress plugin addons. This plugin is useless without Buddypress and rtMedia installed in your site. WDES rtMedia Music has a way to categories/search your music by genre, artist, album, and also capable of adding a featured image in every music uploaded. 
Image is this directly save into WordPress uploads and can be found in media dashboard page. Install now to find out more usefull things features.
 * Text Domain: wdes-music
 * Version: 1.1.2
 * Author: <a href="https://www.anthonycarbon.com/">Anthony Carbon</a>
 * Author URI: https://www.anthonycarbon.com/
 * Donate link: https://www.paypal.me/anthonypagaycarbon
 * Tags: Music, Genre, Artist, Audio, Media, rtMedia, Buddypress, Music Genre, Music Image, Music Author, Music Artist, Mp3, anthonycarbon.com
 * Requires at least: 4.4
 * Tested up to: 5.0
 * Stable tag: 1.1.2
 **/

if ( ! defined( 'ABSPATH' ) ){ exit; }
if ( ! class_exists( 'RTMedia' ) ) { return; }
	
class WDES_Music{
	public static function instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new WDES_Music;
			$instance->constants();
			$instance->requires();
			$instance->globals();
			$instance->actions();
		}
		return $instance;
	}
	private function constants(){
		if ( ! defined( 'WDES_MUSIC' ) ){
			define( 'WDES_MUSIC', 'wdes-music' );
		}
		if ( ! defined( 'WDES_MUSIC_URL' ) ){
			define( 'WDES_MUSIC_URL', plugin_dir_url(__FILE__) );
		}
		if ( ! defined( 'WDES_MUSIC_IMG' ) ){
			define( 'WDES_MUSIC_IMG', WDES_MUSIC_URL . 'assets/images' );
		}
		if ( ! defined( 'WDES_MUSIC_TEMPLATE' ) ){
			define( 'WDES_MUSIC_TEMPLATE', WDES_MUSIC_URL . '/templates' );
		}
		if ( ! defined( 'WDES_MUSIC_PATH' ) ){
			define( 'WDES_MUSIC_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'WDES_MUSIC_PATH_LIB' ) ){
			define( 'WDES_MUSIC_PATH_LIB', WDES_MUSIC_PATH . '/lib' );
		}
		if ( ! defined( 'WDES_MUSIC_PATH_TEMPLATE' ) ){
			define( 'WDES_MUSIC_PATH_TEMPLATE', WDES_MUSIC_PATH_LIB . '/templates' );
		}
		if ( ! defined( 'WDES_MUSIC_PATH_CORE' ) ){
			define( 'WDES_MUSIC_PATH_CORE', WDES_MUSIC_PATH_LIB . '/core' );
		}
		if ( ! defined( 'WDES_MUSIC_PATH_FUNCTIONS' ) ){
			define( 'WDES_MUSIC_PATH_FUNCTIONS', WDES_MUSIC_PATH_LIB . '/functions' );
		}
		if ( ! defined( 'WDES_MUSIC_PARENT_THEME_DIR' ) ){
			define( 'WDES_MUSIC_PARENT_THEME_DIR', get_template_directory() );
		}
		if ( ! defined( 'WDES_MUSIC_CHILD_THEME_DIR' ) ){
			define( 'WDES_MUSIC_CHILD_THEME_DIR', get_stylesheet_directory() );
		}
	}
	private function requires(){
		require_once( WDES_MUSIC_PATH_CORE . '/actions.php' );
		require_once( WDES_MUSIC_PATH_CORE . '/globals.php' );
		require_once( WDES_MUSIC_PATH_CORE . '/settings.php' );
		require_once( WDES_MUSIC_PATH_CORE . '/template.php' );
		require_once( WDES_MUSIC_PATH_FUNCTIONS . '/functions.php' );
		require_once( WDES_MUSIC_PATH_FUNCTIONS . '/filters.php' );
		require_once( WDES_MUSIC_PATH_FUNCTIONS . '/enqueue.php' );
	}
	private function actions(){
		do_action( 'wdes_music_actions', $this );
	}
	private function globals(){
		do_action( 'wdes_music_globals', $this );
	}
}
$GLOBALS['wdes_music'] = WDES_Music::instance();