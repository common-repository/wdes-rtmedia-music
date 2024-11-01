<?php

if ( !defined('ABSPATH') ){ exit; }

function wdes_music_admin_styles(){
 	wp_register_style( 'wdes-music-admin', WDES_MUSIC_URL . 'assets/css/music-admin.css' );
 	wp_enqueue_style( 'wdes-music-admin' );
}

function wdes_music_admin_scripts() {
 	wp_enqueue_style( 'wp-color-picker' ); 
 	wp_enqueue_script( 'wp-color-picker' ); 
 	wp_enqueue_media();
 	wp_register_script( 'wdes-music-admin', WDES_MUSIC_URL . 'assets/js/music-admin.js', array( 'jquery' ) );
 	wp_enqueue_script( 'wdes-music-admin' );
	wp_localize_script(
		'wdes-music-admin',
		'wdes_music',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'class' => 'wdes-music-'
		)
	);
}

function wdes_music_enqueue(){
	global $wdes_music, $bp;
	if( isset( $_GET['wdes_music_debug'] ) ) {
		print_r( $wdes_music );
		print_r( $bp );
	}
	
 	wp_register_script( 'wdes-music', WDES_MUSIC_URL . 'assets/js/music.js', array( 'jquery' ) );
 	wp_register_style( 'wdes-music', WDES_MUSIC_URL . 'assets/css/music.css' );	
 	if( $bp->current_component == 'all-music' || ( $bp->current_component == 'media' && is_numeric( $bp->current_action ) ) ){
		wp_enqueue_script( 'wdes-music' );
	}
 	wp_enqueue_style( 'wdes-music' );
	$musics = array();
	if( wdes_music_ids() ) {
		foreach( wdes_music_ids() as $id ){
			$musics[] = wdes_music_get_media_meta( 'meta_value', $id );
		}
	}
	$localize = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'class' => array( 'wdes-music-', '#wdes-music-', '.wdes-music-' ),
			'text' => array(
				'as' => wdes_music_as_name(),
				'has' => wdes_music_has_name()
			),
			'music' => $musics,
			'music_button' => wdes_music_option( 'wdes_music_enable_music_button' ),
			'music_button_url' => do_shortcode( wdes_music_option( 'wdes_music_view_music_button_url' ) ),
			'music_button_text' => wdes_music_option( 'wdes_music_view_music_button_text' ) ? : __( 'View Music', WDES_MUSIC ),
			'cancel_and_start_upload' => wdes_music_option( 'wdes_music_cancel_start_upload_button' )
		);
	wp_localize_script(
		'wdes-music',
		'wdes_music',
		apply_filters( 'wdes_music_localize_script', $localize )
	);
	$action_variables = '';
	if( ! empty( $bp->action_variables ) ){
		$action_variables = $bp->action_variables[0];
	}
	if( 
		( $wdes_music->slug == $bp->current_component && $wdes_music->upload_music->slug == $bp->current_action ) || 
		( $action_variables == 'edit' && $bp->current_component == 'media' ) 
	){
		wp_enqueue_media();
	}
}

function wdes_music_wp_head(){
 	if( wdes_music_page() ){
		?><style>#subnav li[id="<?php echo wdes_music_page(); ?>-personal-li"]{display:none;}</style><?php
	}	
}