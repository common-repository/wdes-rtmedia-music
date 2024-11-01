<?php

add_action( 'wdes_music_globals', 'wdes_music_globals', 5 );
function wdes_music_globals( $global ){
	$global->slugs = wdes_music_slugs();
	$name = wdes_music_global_names();
	$global->name = wdes_music_get_name();
	$global->slug = wdes_music_get_slug();
	$global->names = $name;
	$global->page_slugs = wdes_music_page_slugs();
	$global->user = new stdClass();
	$global->user->displayed_id = wdes_music_get_displayed_user_id();
	$global->user->user_nicename = wdes_music_get_displayed_user_nicename();
	$global->query = wdes_music_query();
	$global->upload_music = new stdClass();
	$global->upload_music->name = wdes_music_get_upload_name();
	$global->upload_music->slug = wdes_music_get_upload_slug();
}

function wdes_music_slugs(){
	$urls = explode( wdes_music_get_slug() . '/', $_SERVER['REQUEST_URI'] );
	$all_slug = array( wdes_music_get_slug() );
	if( isset( $urls[1] ) ){
		$all_slug = array_filter( array_merge( $all_slug, explode( '/', $urls[1] ) ) );
	}
	return $all_slug;
}