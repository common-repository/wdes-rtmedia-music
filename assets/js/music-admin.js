jQuery(document).ready(function($){
	var timeout = ( function(){
 		var timers = {};
 		return function( callback, ms, x_id ){
		   if ( !x_id ){ x_id = ''; }
		   if ( timers[x_id] ){ clearTimeout( timers[x_id] ); }
		   timers[x_id] = setTimeout( callback, ms );
 		};
	})(),pclas,ptext,toggle;
	$('.wdes-music-genre .wdes-music-genre-add').click(function(e) {
        $(this).before('<span><input type="text" name="rtmedia-options[wdes_music_genre][]" id="wdes_music_genre" value=""><i>x</i></span>');
    });
	$( this ).delegate( '.wdes-music-genre p i', 'click', function() {
		$(this).parent('span').remove();
    });
	$( '.wdes-music-toggle' ).click( function(){
		toggle = $( this ).attr( 'data-toggle' );
		$( '.wdes-music-group' ).not( '.' + toggle ).slideUp();
		$( '.' + toggle ).not().slideDown();
		$( '#wdes_music_active' ).val( toggle );
	});
});