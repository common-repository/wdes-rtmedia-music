<?php

if ( !defined('ABSPATH') ){ exit; }

function wdes_music_print( $r, $echo = false ){
	if( isset( $_GET['wdes_debug'] ) && ! $echo ){
		echo "<pre>";
		print_r( $r );
		echo "</pre>";
	}else if( isset( $_GET['wdes_debug'] ) ){
		echo '<pre style="white-space:normal;">';
		echo $r;
		echo "</pre>";
	}
}

function wdes_music_option( $key ){
	$options = get_option( 'rtmedia-options' );
	if( isset( $options[$key] ) && ! empty( $options[$key] ) ){
		return $options[$key];
	}else{
		return;
	}
}

function wdes_music_if( $page ){
	global $rtmedia_query;
	if( $rtmedia_query->media_query['media_type'] == $page ){
		return true;
	}else{
		return false;	
	}
}

function wdes_music_display_none( $value, $current, $echo = true ){
	if( $current != $value && $echo ){
		echo 'style="display:none;"';		
	}else if( $current != $value && ! $echo ){
		return 'style="display:none;"';
	}
}

function wdes_music_get( $get, $value = '' ){
	if( empty( $_GET[$get] ) ){
		return $value;
	}
	if( is_numeric( $_GET[$get] ) ){
		$value = $_GET[$get];
	}
	return $value;
}

function wdes_music_slug( $string, $echo = false, $separator = "-" ){
	$string = strtolower( $string );
	$string = str_replace( ' ', '-', $string );
	$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
	if( $echo == true ){
		echo preg_replace( '/-+/', $separator, $string );
		return;
	}
   	return preg_replace( '/-+/', $separator, $string );
}

function wdes_music_page(){
	$names = wdes_music_global_names();
	$page = 1;
	$urls = explode( $names[0]['slug'] . '/', $_SERVER['REQUEST_URI'] );
	if( ! empty( $urls[1] ) ){
		$url = explode( '/', $urls[1] );
		if( ! empty( $url[0] ) && is_numeric( $url[0] ) ){
			$page = $url[0];
		}
	}
	if( is_numeric( $page ) ){
		return $page;
	}else{
		return;
	}
}

function wdes_music_page_slugs(){
	$subpages = explode( wdes_music_get_slug() . '/', $_SERVER['REQUEST_URI'] );
	if( ! empty( $subpages[1] ) ){
		$subpages = explode( '/', $subpages[1] );
	}
	$subpages = array_merge( array( wdes_music_get_slug() ), array_filter( $subpages ) );
	return $subpages;
}

function wdes_music_get_page_slug( $index ){
	$page_slugs = wdes_music_page_slugs();
	if( ! isset( $page_slugs[$index] ) ){
		return;
	}
	return $page_slugs[$index];
}

function wdes_music_add_new_nav_item(){
	global $wdes_music, $bp;
	if( empty( $wdes_music->names ) ){ return; }
	$parent_url = $bp->displayed_user->domain . $wdes_music->slug . '/';
	if( bp_displayed_user_id() == bp_loggedin_user_id() ){
		$wdes_music->names[] = wdes_music_upload_name(); 
	}
	foreach( $wdes_music->names as $names ){
		$array = array();
		$array['default_subnav_slug'] = $wdes_music->slug;
		$array['screen_function'] = 'wdes_music_all';
		if( ! empty( $names['name'] ) ){
			$array['name'] = $names['name'];
		}
		if( ! empty( $names['slug'] ) ){
			$array['slug'] = $names['slug'];
		}
		if( ! empty( $names['position'] ) ){
			$array['position'] = $names['position'];
		}
		if( ! empty( $names['parent_url'] ) ){
			$array['parent_url'] = $names['parent_url'];
		}
		if( ! empty( $names['default_subnav_slug'] ) ){
			$array['default_subnav_slug'] = $names['default_subnav_slug'];
		}
		if( ! empty( $names['screen_function'] ) ){
			$array['screen_function'] = $names['screen_function'];
		}
		if( $names['menu'] == 'main' ){
			bp_core_new_nav_item( $array );
		}
		if( $names['menu'] == 'submenu' && $names['parent_slug'] == 'null' &&  $names['slug'] ){
			$array['parent_slug'] = $wdes_music->slug;
		}
		if( $names['menu'] == 'submenu' && $names['parent_url'] == 'null' &&  $names['slug'] ){
			$array['parent_url'] = $parent_url;
		}
		if( $names['menu'] == 'submenu' ){
			bp_core_new_subnav_item( $array );
		}
		$array = array();
	}
	if( wdes_music_page() > 1 ){
		$array['default_subnav_slug'] = $wdes_music->slug;
		$array['screen_function'] = 'wdes_music_all';
		$array['name'] = 'Page';
		$array['slug'] = wdes_music_page();
		$array['position'] = 0;
		$array['parent_url'] = $parent_url;
		$array['parent_slug'] = $wdes_music->slug;
		bp_core_new_subnav_item( $array );
	}
}

function wdes_music_upload_music( $filename ){
	global $bp, $wdes_music;
	if( $bp->current_action != $wdes_music->upload_music->slug ){
		return $filename;
	}
	return 'upload-music';
}

function wdes_music_include_template( $filename, $path = null, $mainfilename = false ) {
	$filename = wdes_music_template_filename( $filename );
	if( $mainfilename ){
		$filename = $mainfilename;
	}
	$path = wdes_music_path_template( $path );
	if ( file_exists( WDES_MUSIC_CHILD_THEME_DIR . "/wdes-rtmedia-music/$filename.php" ) ) {
		include_once( WDES_MUSIC_CHILD_THEME_DIR . "/wdes-rtmedia-music/$filename.php" );
		return;
	}
	if ( file_exists( WDES_MUSIC_PARENT_THEME_DIR . "/wdes-rtmedia-music/$filename.php" ) ) {
		include_once( WDES_MUSIC_PARENT_THEME_DIR . "/wdes-rtmedia-music/$filename.php" );
		return;
	}
	if( $path != null && ! $mainfilename ){
		include_once( $path . "/$filename.php" );
	}else{
		include_once( WDES_MUSIC_PATH_TEMPLATE . "/$filename.php" );
	}
}

function wdes_music_wpdb( $method, $select, $type = OBJECT ){
	global $wpdb;
	/*$x = "'{$column}'";
	if( $column ){
		$results = $wpdb->$method( $select, $type );
		$ids = array_map( function( $entry ){
			echo $x;
			return $entry[$x];
		}, $results );
		return $ids;
	}*/
	if( ! $type ){
		return $wpdb->$method( $select );
	}
	return $wpdb->$method( $select, $type );
}

function wdes_music_title( $id ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	return stripslashes( esc_html( $wpdb->get_var( "SELECT media_title FROM {$prefix}rt_rtm_media WHERE media_type = 'music' AND id = $id" ) ) );
}

function wdes_music_get_media( $column, $id ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	return wdes_music_wpdb( "get_var", "SELECT $column FROM {$prefix}rt_rtm_media WHERE media_type = 'music' AND id = $id", false );
}

function wdes_music_get_media_meta( $column, $id, $key = 'duration_time' ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	return wdes_music_wpdb( "get_var", "SELECT $column FROM {$prefix}rt_rtm_media_meta WHERE media_id = $id AND meta_key = '$key'", false );
}

function wdes_music_author_nicename( $id ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	return wdes_music_wpdb( "get_var", "SELECT user_nicename FROM {$prefix}users WHERE ID = $id", false );
}

function wdes_music_get_user_id( $column, $value ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	return wdes_music_wpdb( "get_var", "SELECT ID FROM {$prefix}users WHERE {$column} = '$value'", false );
}

function wdes_music_get_user( $column, $id ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	return wdes_music_wpdb( "get_var", "SELECT $column FROM {$prefix}users WHERE ID = $id", false );
}

function wdes_music_get_all_users_id(){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$ids = wdes_music_wpdb( "get_results", "SELECT ID FROM {$prefix}users", ARRAY_A );
	$ids = array_map( function( $entry ){
		return $entry['ID'];
	}, $ids );
	return $ids;
}

function wdes_music_found(){
	$sql = wdes_music_sql_select();
	return count( wdes_music_wpdb( "get_results", "$sql LIMIT 0, 999999" ) );
}

function wdes_music_nav_count(){
	$output = '';
	if( wdes_music_found() ){
		$total = ( wdes_music_found() / wdes_music_per_page() );
		$output = floor( $total );
		if( $total > $output ){
			$output = ( $output + 1 );
		}
	}
	return $output;
}

function wdes_music_offset(){
	global $wp_query;
	$offset = 0;
	$page = ! empty( $wp_query->query_vars ) ? : false;
	if( isset( $page['page'] ) ){
		$offset = ( $page['page'] - 1 ) * wdes_music_per_page();
	}
	return $offset;
}

function wdes_music_query(){
	$std = new stdClass();
	$std->link = wdes_music_url();
	$std->page = wdes_music_page();
	$std->music_per_page = wdes_music_per_page();
	$std->found = wdes_music_found();
	$std->nav_count = wdes_music_nav_count();
	return $std;
}

function wdes_music_ajax_in_search_field(){
	$request = $_POST['request'];
	$name = $_POST['name'];
	$select = wdes_music_sql_select( $request ) . " LIMIT 0, 50";
	$results = array_map( function( $entry ){
	 	return $entry['id'];
 	}, wdes_music_wpdb( "get_results", $select, ARRAY_A ) );
	if( $results ){
		echo count( $results ) > 10 ? '<div class="wdes-music-scroll">' : '';
		foreach( $results as $result ){
			if( $name == 'search' ){
				$title = wdes_music_get_media( 'media_title', $result );
				printf( "<span>%s</span>", ucwords( $title ) );
			}
			if( $name == 'duration' ){
				printf( "<span>%s</span>", wdes_music_get_media_meta( 'meta_value', $result ) );
			}
		}
		echo count( $results ) > 10 ? '</div>' : '';
	}else{
		printf( "<span>%s</span>", __( "No Results Found", WDES_MUSIC ) );
	}
	die();
}

function wdes_music_pagination_links(){
	global $wp_query;
	$max = wdes_music_nav_count();
	$paged = 1;
	if( empty( $wp_query->query_vars ) ){
		return array();
	}
	if( $wp_query->query_vars['page'] > 1 ){
		$paged = $wp_query->query_vars['page'];
	}
	$links = array();
	$limit = 5;
	if ( $max > 1 && $max < $limit ){
		for( $x = 1; $x <= $max; $x++ ){
			$links[] = $x;
		}
	}
	if ( $max < $limit ){
		return $links;
	}
	if ( $paged == 1 ){
		$links[] = $paged;
		$links[] = $paged + 1;
		$links[] = $paged + 2;
		$links[] = $paged + 3;
		$links[] = $paged + 4;
		wdes_music_print( 'paged 1:', true );
	}
	if ( $paged == 2 ){
		$links[] = $paged - 1;
		$links[] = $paged;
		$links[] = $paged + 1;
		$links[] = $paged + 2;
		$links[] = $paged + 3;
		wdes_music_print( 'paged 2:', true );
	}
	if ( $paged >= 3 && ( $paged + 2 ) <= $max ) {
		$links[] = $paged - 2;
		$links[] = $paged - 1;
		$links[] = $paged;
		$links[] = $paged + 1;
		$links[] = $paged + 2;
		wdes_music_print( 'paged 3:', true );
	}
	if ( $paged > ( $max - 2 ) && ( $paged - 2 ) <= $max && $paged != $max ) {
		$links[] = $paged - 3;
		$links[] = $paged - 2;
		$links[] = $paged - 1;
		$links[] = $paged;
		$links[] = $paged + 1;
		wdes_music_print( 'paged 4:', true );
	}
	if ( $paged == $max ) {
		$links[] = $paged - 4;
		$links[] = $paged - 3;
		$links[] = $paged - 2;
		$links[] = $paged - 1;
		$links[] = $paged;
		wdes_music_print( 'paged 5:', true );
	}
	if ( ! in_array( $max, $links ) && $paged > 2 && ( $max - ( $paged + 2 ) ) == 1 ){
		$links[] = $max;
		wdes_music_print( 'paged 6:', true );
	}
	if( ( $paged + 3 ) < $max && $links[4] != $max ){
		$links[] = '...';
		wdes_music_print( 'paged 7:', true );
	}
	if ( ( $paged + 6 ) < $max ){
		if( ( $max - $links[4] ) <= $limit ){
			$limit = ( $max - $links[4] );
		}
		$links[] = $links[4] + $limit;
		wdes_music_print( 'paged 8:', true );
	}
	if ( ! in_array( $max, $links ) && ( $max - ( $paged + 2 ) ) <= 2 ){
		$links[] = $max;
		wdes_music_print( 'paged 9:', true );
	}
	if ( ! in_array( ( $links[4] + ( $max - $links[4] ) ), $links ) && ( $max - $links[4] ) <= $limit ){
		$limit = ( $max - $links[4] );
		$links[] = $links[4] + $limit;
		wdes_music_print( 'paged 10:', true );
	}
	wdes_music_print( "paged - {$links[4]}:", true );
	return $links;
}

function wdes_music_url(){
	global $wp_query;
	if( isset( $_GET['debug'] ) ) :
		print_r($wp_query);
	endif;
	if( empty( $wp_query->queried_object_id ) ){
		return get_bloginfo( 'url' );
	}
	if( get_permalink( $wp_query->queried_object_id ) ){
		return get_permalink( $wp_query->queried_object_id );
	}
	return get_bloginfo( 'url' ) . '/' . $wp_query->query['pagename'] . '/';
}

function wdes_music_get_name(){
	return wdes_music_option( 'wdes_music_all_music_name' ) ? : __( 'All Music', WDES_MUSIC );
}

function wdes_music_get_slug(){
	return wdes_music_slug( wdes_music_get_name() );
}

function wdes_music_get_displayed_user_nicename(){
	$urls = explode( '/' . wdes_music_get_slug(), $_SERVER['REQUEST_URI'] );
	$urls = explode( '/', $urls[0] );
	return array_pop( $urls );
}

function wdes_music_get_displayed_user_id( $user_nicename = false ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	if( ! $user_nicename ){
		$user_nicename = wdes_music_get_displayed_user_nicename();
	}
	$user_id = wdes_music_wpdb( "get_var", "SELECT ID FROM {$prefix}users WHERE user_nicename = '{$user_nicename}'", false );
	return apply_filters( "wdes_music_displayed_user_id", $user_id );
}

function wdes_music_get_upload_name(){
	return wdes_music_option( 'wdes_music_upload_music_name' ) ? : __( 'Upload Music', WDES_MUSIC );
}

function wdes_music_get_upload_slug(){
	return wdes_music_slug( wdes_music_get_upload_name() );
}

function wdes_music_upload_name(){
	$upload = array(
			'name' => wdes_music_get_upload_name(),
			'menu' => 'submenu',
			'main_slug' => 'upload-music',
			'slug' => wdes_music_get_upload_slug(),
			'parent_slug' => 'null',
			'parent_url' => 'null',
			'position' => 5,
		);
	return $upload;
}

function wdes_music_as_name(){
	return wdes_music_option( 'wdes_music_as_name' ) ? __( wdes_music_option( 'wdes_music_as_name' ), WDES_MUSIC ) : __( 'Advance Search', WDES_MUSIC );
}

function wdes_music_has_name(){
	return wdes_music_option( 'wdes_music_has_name' ) ? __( wdes_music_option( 'wdes_music_has_name' ), WDES_MUSIC ) : __( 'Hide Advance Search', WDES_MUSIC );
}

function wdes_music_pagination_link( $paged, $query = false, $echo = true ){
	if( $paged == 1 ){
		$paged = '';
	}
	if( $echo ){
		echo wdes_music_url() . $paged . $query;
	}else{
		return wdes_music_url() . $paged . $query;
	}
}

function wdes_music_previous_link( $paged, $query = false, $echo = true ){
	if( $echo ){
		$link = ($paged - 1) > 1 ? ($paged - 1) : '';
		echo wdes_music_url() . $link . $query; 
	}else{
		$link = ($paged - 1) > 1 ? ($paged - 1) : '';
		return wdes_music_url() . $link . $query;
	}
}

function wdes_music_next_link( $paged, $query = false, $echo = true ){
	if( $echo ){
		echo wdes_music_url() . ($paged + 1) . $query;
	}else{
		return wdes_music_url() . ($paged + 1) . $query;
	}
}

function wdes_music_get_image_url( $media_id, $size = 'full' ){
	$image_id = get_rtmedia_meta( $media_id, 'wdes_music_featured_image' );
	if( empty( $image_id ) ) { return; }
	$imagurl = wp_get_attachment_image_src( $image_id, $size, false );
	if( empty( $imagurl[0] ) ) { return; }
	return $imagurl[0];
}