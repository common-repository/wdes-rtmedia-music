jQuery( document ).ready( function( $ ){
	var timeout = ( function(){
 		var timers = {};
 		return function( callback, ms, x_id ){
		   if ( !x_id ){ x_id = ''; }
		   if ( timers[x_id] ){ clearTimeout( timers[x_id] ); }
		   timers[x_id] = setTimeout( callback, ms );
 		};
	})(), magic = ( function(){
		return function(x1,callback,x2){
			if (! x2){x2 = 'click';}
			if (x1 && callback){$(document).delegate(x1,x2,callback);} 
		};
	})(),li_ID, music_meta = {},s,id,names = {},genre_selected = [],media,mc_request = {},mc_count = 0,wdes_music_media_featured_image;
	$(mc(1,'search ')+mc(2,'toggle span')).click(function(){
		$(mc(1,'search ')+mc(2,'advance')).slideToggle();
		if( $(this).text() == wdes_music['text']['as'] ){
			$(this).text( wdes_music['text']['has'] );
		}else{
			$(this).text( wdes_music['text']['as'] );
		}
	});
	$(mc(2,'ajax')).bind( 'paste', function(){
		id = '#' + $(this).attr('id');
		music_search( id );
	});
	$(mc(2,'ajax')).keyup( function(){
		id 	= '#' + $(this).attr('id');
		music_search( id );
	});
	magic(mc(1,'sr span'),function(){
		$(id).val($(this).text());
	});
	$(this).click(function(){
		if(!$(this).hasClass(mc(0,'ajax'))){
			$(mc(1,'sr')).remove();
		}
	});
	if($(mc(2,'featured-image')).length && wdes_music['featured_image']){
		$(mc(2,'featured-image')).click(function(){
			if(wdes_music_media_featured_image){
				wdes_music_media_featured_image.open();
				return;
			}
			wdes_music_media_featured_image = wp.media.frames.file_frame = wp.media({
				title : 'Choose an image',
				button : { text: 'Choose image' },
				multiple : false,
				library : { type : 'image' }
			});
			wdes_music_media_featured_image.on('select', function(){
				attachment = wdes_music_media_featured_image.state().get('selection').first().toJSON();
				attachment_id = attachment.id;
				$(mc(2,'featured-image')).addClass(mc(2,'image-loading'));
				$.ajax({
					url : wdes_music['ajaxurl'],
					type : 'post',
					data : {
						action : 'wdes_music_image',
						id : attachment_id,
					},
					success : function(data){
						$(mc(1,'featured-image')).val(attachment_id);
						$(mc(2,'featured-image')).css('background-image','url("'+data+'")');
						$(mc(2,'featured-image')).removeClass(mc(2,'image-loading'));
					}
				});
			});
			wdes_music_media_featured_image.open();
		});
	}
	//if(typeof rtMediaHook == 'object' && (wdes_music['genre'] || wdes_music['artist'] || wdes_music['featured_image']) && $(mc(2,'upload')).length){
	if(typeof rtMediaHook == 'object' && wdes_music['genre'] && $(mc(2,'upload')).length){
		rtMediaHook.register("rtmedia_js_after_files_added",function( args ){
			if(! args){return;}
			$('.wdes-music-cancel-start-btn').css('display','none');
			if($(mc(2,'upload #wdes_music_success')).length){
				$(mc(2,'upload #wdes_music_success')).remove();
			}
			//if( wdes_music['genre'] ){
				if(! $(mc(2,'upload #wdes_music_error')).length){
					$(mc(2,'upload #drag-drop-area')).after('<div id="wdes_music_error">' + wdes_music['genre']['error_message'] + '</div>');
				}
			//}
			$(mc(2,'upload input.start-media-upload')).attr( 'disabled', 'disabled' );
			$(mc(2,'upload #drag-drop-area')).addClass(mc(0,'error')).removeClass(mc(0,'complete'));
			for(x = 0;x < args[1].length;x++){
				$(mc(2,'upload span#label_' + args[1][x]['id'])).trigger( 'click' );
				if( ! $( '#file_thumb_' + args[1][x]['id'] ).find( 'i' ).length && wdes_music['featured_image']){
					$( '#file_thumb_' + args[1][x]['id'] ).append( '<i class="wdes-music-image-i">Click Add Image</i>' );
					$(mc(2,'upload span#save_' + args[1][x]['id'])).hide();
				}
				$(mc(2,'upload span#label_' + args[1][x]['id'])).hide();
				if(! $( '#wdes_music_genre_' + args[1][x]['id'] ).length && wdes_music['genre']){
					$(mc(2,'upload #rtm_desc_wp_' + args[1][x]['id'])).after('<div id="wdes_music_genre_' + args[1][x]['id'] + '" class="wdes-music-genre" data-index="'+x+'"><label>Select '+wdes_music['genre']['name']+':</label><div class="group">'+wdes_music['genre']['list']+'</div></div>');
					$(mc(2,'upload span#save_' + args[1][x]['id'])).hide();
				}
				if(! $( '#wdes_music_artist_' + args[1][x]['id'] ).length && wdes_music['artist']){
					if(! music_meta[args[1][x]['id']]){
						music_meta[args[1][x]['id']] = {};
						if(music_meta[music_meta[args[1][x]['id']]]){
							music_meta[music_meta[args[1][x]['id']]]['artist'] = '';
						}
					}
					$(mc(2,'upload #rtm_desc_wp_' + args[1][x]['id'])).after('<div id="wdes_music_artist_' + args[1][x]['id'] + '" class="wdes-music-artist" data-index="'+x+'" data-id="' + x + '" data-index="' + args[1][x]['id'] + '"><label>'+wdes_music['artist']['name']+':</label><div class="group">'+wdes_music['artist']['html']+'</div></div>');
					$(mc(2,'upload span#save_' + args[1][x]['id'])).hide();
				}
			}		
			magic(mc(2,'genre input'),function(){
				li_ID = $(this).parent().parent().parent().parent().parent().attr('id');
				var pid = $(this).parent().parent().parent().attr('id');
				if(li_ID){
					if(! music_meta[li_ID]){ music_meta[li_ID] = {};}
					music_meta[li_ID]['genre'] = $('#' + pid + ' input[name="wdes_music_genre[]"]:checked' ).map(function(){
						return $(this).val();
					}).get();
				}
				$(mc(2,'genre')).each(function(index, element) {
					if( $(this).find('input[name="wdes_music_genre[]"]:checked').length ){
						genre_selected[index] = 1;
					}
				});
				if( $(mc(2,'genre')).length == genre_selected.filter(String).length ){
					$(mc(2,'upload input.start-media-upload')).removeAttr( 'disabled' );
					$(mc(2,'upload #drag-drop-area')).addClass(mc(0,'complete')).removeClass(mc(0,'error'));
					if(wdes_music['cancel_and_start_upload']){
						$('.wdes-music-cancel-start-btn').css('display','block');
					}
				}else{
					$(mc(2,'upload input.start-media-upload')).attr( 'disabled', 'disabled' );
					$(mc(2,'upload #drag-drop-area')).removeClass(mc(0,'complete')).addClass(mc(0,'error'));
					if(wdes_music['cancel_and_start_upload']){
						$('.wdes-music-cancel-start-btn').css('display','none');
					}
				}
				genre_selected = [];
			},'change');		
			magic(mc(2,'upload input.rtm-upload-edit-title'),function(){
				var xindex = $(this).parent().siblings(mc(2,'genre')).attr('data-index');
				li_ID = $(this).parent().parent().parent().attr('id');
				if(li_ID && args[1][xindex]){
					if(! music_meta[li_ID]){ music_meta[li_ID] = {};}
					music_meta[li_ID]['title'] = $(this).val();
					var music_id = args[1][xindex]['id'];
					if(music_meta[music_id]['title']){
						args[1][xindex]['title'] = music_meta[music_id]['title'];
					}
				}
			},'change');		
			magic(mc(2,'upload textarea.rtm-upload-edit-desc'),function(){
				var xindex = $(this).parent().siblings(mc(2,'genre')).attr('data-index');
				li_ID = $(this).parent().parent().parent().attr('id');
				if(li_ID && args[1][xindex]){
					if(! music_meta[li_ID]){ music_meta[li_ID] = {};}
					music_meta[li_ID]['description'] = $(this).val();
					var music_id = args[1][xindex]['id'];
					if(music_meta[music_id]['description']){
						args[1][xindex]['description'] = music_meta[music_id]['description'];
					}
				}
			},'change');
			if($(mc(2,'upload .rtm-upload-edit-artist')).val()){
				var xindex = $(mc(2,'upload .rtm-upload-edit-artist')).parent().parent().parent().siblings(mc(2,'genre')).attr('data-index');
				li_ID = $(mc(2,'upload .rtm-upload-edit-artist')).parent().parent().parent().parent().parent().attr('id');
				if(li_ID && args[1][xindex]){
					if(! music_meta[li_ID]){ music_meta[li_ID] = {};}
					music_meta[li_ID]['artist'] = $(mc(2,'upload .rtm-upload-edit-artist')).val();
					var music_id = args[1][xindex]['id'];
					if(music_meta[music_id]['artist']){
						args[1][xindex]['artist'] = music_meta[music_id]['artist'];
					}
				}
			}
			magic(mc(2,'upload .rtm-upload-edit-artist'),function(){
				var xindex = $(this).parent().parent().parent().siblings(mc(2,'genre')).attr('data-index');
				li_ID = $(this).parent().parent().parent().parent().parent().attr('id');
				if(li_ID && args[1][xindex]){
					if(! music_meta[li_ID]){ music_meta[li_ID] = {};}
					music_meta[li_ID]['artist'] = $(this).val();
					var music_id = args[1][xindex]['id'];
					if(music_meta[music_id]['artist']){
						args[1][xindex]['artist'] = music_meta[music_id]['artist'];
					}
				}
			},'change');
			magic(mc(2,'upload .plupload_file_thumb .wdes-music-image-i'),function(){
				li_ID = $(this).parent().parent().attr('id');
				imgindex = $(this).parent().parent().find(mc(2,'genre')).attr('data-index');
				imgpid = $(this).parent().attr('id');
				if( wdes_music_media_featured_image ) {
					wdes_music_media_featured_image.open();
					return;
				}
				wdes_music_media_featured_image = wp.media.frames.file_frame = wp.media({
					title: 'Choose an image',
					button: { text: 'Choose image' },
					multiple: false,
					library : { type : 'image' }
				});
				wdes_music_media_featured_image.on( 'select', function() {
					$( '#' + imgpid ).find(mc(2,'image-i')).addClass(mc(2,'image-loading'));
					attachment = wdes_music_media_featured_image.state().get( 'selection' ).first().toJSON();
					if( imgindex && imgpid && attachment.id && attachment.url ){
						if(li_ID){
							if(! music_meta[li_ID]){ music_meta[li_ID] = {};}
							music_meta[li_ID]['wdes_music_image_id'] = attachment.id;
						}
						$( '#' + imgpid ).find(mc(2,'image-i')).css( "background-image", "url('" + attachment.url + "')" );
						$( '#' + imgpid ).find(mc(2,'image-i')).text( 'Change Image' );
						$( '#' + imgpid ).find(mc(2,'image-i')).removeClass(mc(2,'image-loading'));
					}
				});
				wdes_music_media_featured_image.open();
			},'click');
			return args;
		});
		rtMediaHook.register("rtmedia_js_before_file_upload",function(args){
			var music_id = args[1]['id'];
			if(music_meta[music_id]['title']){
				args[1]['title'] = music_meta[music_id]['title'];
			}
			if(music_meta[music_id]['description']){
				args[1]['description'] = music_meta[music_id]['description'];
			}
			if(music_meta[music_id]['genre']){
				args[1]['genre'] = music_meta[music_id]['genre'];
			}
			if(music_meta[music_id]['artist']){
				args[1]['artist'] = music_meta[music_id]['artist'];
			}
			if(music_meta[music_id]['wdes_music_image_id']){
				args[1]['wdes_music_image_id'] = music_meta[music_id]['wdes_music_image_id'];
			}
			return args;
		});
		rtMediaHook.register("rtmedia_js_after_file_upload",function(args){
			if(! args){return;}
			$(mc(2,'upload #drag-drop-area')).removeClass(mc(0,'complete'));
			media = JSON.parse(args[2]);
			if(args[1]['genre']){
				$.ajax({
					url		: wdes_music['ajaxurl'],
					type	: 'post',
					data	: {
								action : 'wdes_music_genre',
								wdes_music_id : media.media_id,
								wdes_music_genre : args[1]['genre'],
							},
					success	: function(data){
						if($(mc(2,'upload #wdes_music_error')).length){
							$(mc(2,'upload #wdes_music_error')).remove();
						}
						if(! $(mc(2,'upload #wdes_music_success_div')).length){
							$(mc(2,'upload #drag-drop-area')).after('<div id="wdes_music_success_div"></div>');
						}
						if( $(mc(2,'upload #wdes_music_success_div')).length && ! $(mc(2,'upload .wdes_music_success-' + media.media_id)).length ){
							$(mc(2,'upload #wdes_music_success_div')).append('<div id="wdes_music_success" class="wdes_music_success-' + media.media_id + '">' + args[1]['title'] + ' ' + wdes_music['genre']['success_message'] + '</div>');
							if($(mc(2,'upload #wdes_music_success_div')).length && ! $(mc(2,'upload #wdes_music_view_music')).length && wdes_music['music_button']){
								$(mc(2,'upload #wdes_music_success_div')).after('<div id="wdes_music_view_music"><a href="'+wdes_music['music_button_url']+'" class="button">'+wdes_music['music_button_text']+'</a></div>');
							}
						}	
					}
				});
			}
			if(args[1]['artist']){
				$.ajax({
					url		: wdes_music['ajaxurl'],
					type	: 'post',
					data	: {
								action : 'wdes_music_artist',
								wdes_music_id : media.media_id,
								wdes_music_artist : args[1]['artist'],
							},
					success	: function(data){
						//console.log(data);
					}
				});
			}
			if(args[1]['wdes_music_image_id']){
				$.ajax({
					url		: wdes_music['ajaxurl'],
					type	: 'post',
					data	: {
								action : 'wdes_music_save_image',
								wdes_music_id : media.media_id,
								wdes_music_image : args[1]['wdes_music_image_id'],
							},
					success	: function(data){
						//console.log(data);
					}
				});
			}
		});
	}
	magic(mc(2,'cancel-start-btn .start-upload-btn'),function(){
		$('.wdes-music-upload .start-media-upload').trigger('click');
		$('.wdes-music-cancel-start-btn').css('display','none');
 	});
	magic(mc(2,'cancel-start-btn .cancel-btn'),function(){
		$('.wdes-music-upload .plupload_delete .remove-from-queue' ).trigger('click');
		$('.wdes-music-cancel-start-btn').css('display','none');
 	});
	$(window).bind('load',function(){
		if(wdes_music['music']){
			for(x = 0; x < wdes_music['music'].length; x++){
				$('.wdes-music-group .wdes-music-item').eq(x).find('.mejs-duration').text(wdes_music['music'][x]);
			}
		}
	});
	function music_search( id ){
		name = $(id).attr('name');
		if(! $(id).val()){ $(mc(1,'sr')).remove(); return; }
		$(mc(1,'sr')).html('<span class="'+mc(0,'loading')+'">&nbsp;</span>');
		if(!$(mc(1,'sr')).length){
			$(id).parent().append('<div id="'+mc(0,'sr')+'"><span class="'+mc(0,'loading')+'">&nbsp;</span></div>').css('width',$(id).outerWidth());
		}
		timeout(function(){
			$(mc(2,'ajax')).each(function(index, element){
				if($(this).val()){
					mc_request[$(this).attr('name')] = { val: $(this).val(), name: $(this).attr('name') };
					mc_count = mc_count + 1;
				}
			});
			mc_count = 0;
			$.ajax({
				url		: wdes_music['ajaxurl'],
				type	: 'post',
				data	: {
							action	: 'wdes_music_aisf',
							request	: mc_request,
							name	: name	
						},
				success	: function(data){
					$(mc(1,'sr')).html(data);
					mc_request = {};
				}
			});
		},200);	}
	function music_ajax_search_data(){
		var value = [];
		var output = '';
		$(mc(2,'ajax')).each(function(index, element){
			if($(this).val()){
				mc_request[$(this).attr('name')] = $(this).val();
			}
        });
		//return value;
	}
	function mc(index,after){
		switch(index){
			case 0:
				return wdes_music['class'][index] + after;
				break;
			case 1:
				return wdes_music['class'][index] + after;
				break;
			case 2:
				return wdes_music['class'][index] + after;
				break;
		}
	}
});