<?php

if ( !defined('ABSPATH') ){ exit; }

function wdes_music_global_names(){
	$names = array(
		array(
			'name' => wdes_music_get_name(),
			'menu' => 'main',
			'main_slug' => 'all-music',
			'slug' => wdes_music_get_slug(),
			'position' => 999
		)
	);
	return apply_filters( 'wdes_music_global_names', $names );
}

function wdes_music_sql_select( $ajax = false ){
	global $bp,$wpdb;
	$prefix = $wpdb->prefix;
	$s = '';
	$group = '';
	$leftjoin = '';
	$search = wdes_music_get( 'search' ) ? explode( ' ', wdes_music_get( 'search' ) ) : array();
	$duration = wdes_music_get( 'duration' );
	$order = wdes_music_get( 'order', 'DESC' );
	$displayed_id = wdes_music_get_displayed_user_id();
	$orderby = wdes_music_get( 'orderby', 'upload_date' );
	if( $ajax ){
		$search = $ajax['search']['val'] ? explode( ' ', $ajax['search']['val'] ) : array();
		$duration = $ajax['duration']['val'];
		$order = $ajax['order']['val'] ? : 'DESC';
		$orderby = $ajax['orderby']['val'] ? : 'upload_date';
		$urls = explode( '/' . wdes_music_get_slug(), $bp->displayed_user->domain );
		$urls = array_filter(explode( '/', $urls[0] ));
		$displayed_id = wdes_music_get_displayed_user_id( array_pop( $urls ) );
		if( $ajax ){
			foreach( $ajax as $key ){
				if( ! empty( $key['val'] ) && ! empty( $key['name'] ) && ! in_array( $key['name'], array( 'duration', 'order', 'orderby', 'search' ) ) ){
#					echo "wdes_music_sql_left_join_ajax_search_" .$key['name'];
					$leftjoin .= apply_filters( "wdes_music_sql_left_join_ajax_search_" . $key['name'], $key['val'] );
					$s .= apply_filters( "wdes_music_sql_where_statement_ajax_search_" . $key['name'], $key['val'] );
					#$group .= apply_filters( "wdes_music_sql_group_statement_ajax_search_" . $key['name'], $key['val'] );
				}
			}
		}
	}
	if( $search ){
		foreach( $search as $key ){
			$s .= " AND (wdes_media.media_title LIKE '%$key%')";
		}
	}
	if( $displayed_id ){
		$s .= " AND wdes_media.media_author = $displayed_id";
	}
	if( $duration || is_numeric( $duration ) ){
		$leftjoin .= " LEFT JOIN {$prefix}rt_rtm_media_meta as wdes_duration ON wdes_duration.meta_key = 'duration_time' AND wdes_duration.media_id = wdes_media.id";
		$s .= " AND wdes_duration.meta_value LIKE '%$duration%'";
	}
	$leftjoin = apply_filters( "wdes_music_sql_left_join", $leftjoin );
	$s = apply_filters( "wdes_music_sql_where_statement", $s );
	$group = apply_filters( "wdes_music_sql_group_statement", $group );
	return apply_filters( "wdes_music_sql_select", "SELECT wdes_media.id FROM {$prefix}rt_rtm_media as wdes_media {$leftjoin} WHERE wdes_media.media_type = 'music'{$s} {$group} ORDER BY wdes_media.{$orderby} {$order}" );
}

function wdes_music_ids(){
	$music_per_page = wdes_music_per_page();
	$offset = wdes_music_offset();
	$sql = wdes_music_sql_select();
	$select = "$sql LIMIT $offset, $music_per_page";
	wdes_music_print( $select, true );
	$ids = wdes_music_wpdb( "get_results", $select, ARRAY_A );
	$ids = array_map( function( $entry ){
		return $entry['id'];
	}, $ids );
	return apply_filters( 'wdes_music_ids', $ids );
}

function wdes_music_per_page(){
 	return apply_filters( 'wdes_music_per_page', wdes_music_option( 'wdes_music_per_page' ) ? :  wdes_music_option( 'general_perPageMedia' ) );
}

function wdes_music_get_query(){
	$queries = apply_filters( "wdes_music_get_query", array( 'search', 'duration', 'orderby', 'order' ) );
	$output = '';
	$separator = '/?';
	foreach( $queries as $query ){
		if( wdes_music_get( $query ) ){
			$output .= $separator . "$query=" . wdes_music_get( $query );
			$separator = '&';
		}
	}
	return $output;
}

function wdes_music_template_filename( $filename = '' ){
	return apply_filters( 'wdes_music_template_filename', $filename );
}

function wdes_music_path_template( $path = '' ){
	return apply_filters( 'wdes_music_path_template', $path );
}