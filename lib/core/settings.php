<?php

if ( !defined('ABSPATH') ){ exit; }

function wdes_music_tab( $tabs ){
	$tabs[] = array(
		'href'     => '#rtmedia-wdes-music',
		'icon'     => 'dashicons-admin-tools',
		'title'    => __( 'WDES Music', WDES_MUSIC ),
		'name'     => __( 'WDES Music', WDES_MUSIC ),
		'callback' => 'wdes_music_tab_content',
	);
 	return $tabs;
}

function wdes_music_tab_content(){
	global $wdes_music;
	$name = $wdes_music->name;
	$active = wdes_music_option( 'wdes_music_active' ) ? wdes_music_option( 'wdes_music_active' ) : 'wdes-music-names';
	?>
    <input type="hidden" name="rtmedia-options[wdes_music_active]" id="wdes_music_active" value="<?php echo $active; ?>" />
    <div id="rtmedia-wdes-music">
        <div id="wdes-music-names">
        <h6 class="wdes-music-toggle" data-toggle="wdes-music-names"><?php _e( 'Global Names', WDES_MUSIC ); ?></h6>
        <div class="wdes-music-group wdes-music-names"<?php wdes_music_display_none( $active, 'wdes-music-names' ); ?>>
		<?php do_action( 'wdes_music_admin_before_names' ); ?>
        <div class="rtm-option-wrapper">
            <h3 class="rtm-option-title">
                <span class="name"><?php _e( 'Names', WDES_MUSIC ); ?></span>
                <?php wdes_music_tooltip( "Change the global name to your choice. Example from \"{$name}\" to \"Music Category\"." ); ?>
            </h3>
            <div class="global-name">
                <div class="wdes-music-field">
                    <p><?php
                    if( $wdes_music->names ){
                        foreach( $wdes_music->names as $names ){
                            ?>
                            <span>
                                <strong><?php _e( $names['name'], WDES_MUSIC ); ?> :</strong>
                                <input type="text" name="rtmedia-options[wdes_music_<?php wdes_music_slug( $names['main_slug'], true, "_" ); ?>_name]" id="wdes_music_name" value="<?php _e( $names['name'], WDES_MUSIC ); ?>" placeholder="<?php _e( $names['name'], WDES_MUSIC ); ?>" />
                            </span>
                            <?php
                        }
                    }
                    ?>
                    <span>
                    	<strong>Advance Search :</strong>
                        <input type="text" name="rtmedia-options[wdes_music_as_name]" id="wdes_music_as_name" value="<?php echo wdes_music_as_name(); ?>" placeholder="<?php echo wdes_music_as_name(); ?>" />
                 	</span>
                    <span>
                    	<strong>Hide Advance Search :</strong>
                        <input type="text" name="rtmedia-options[wdes_music_has_name]" id="wdes_music_has_name" value="<?php echo wdes_music_has_name(); ?>" placeholder="<?php echo wdes_music_has_name(); ?>" />
                 	</span>
                    </p>
                </div>
            </div>
        </div>
        </div>
        </div>
        <div id="wdes-music-all-music">
        <h6 class="wdes-music-toggle" data-toggle="wdes-music-all-music"><?php _e( $name, WDES_MUSIC ); ?></h6>
        <div class="wdes-music-group wdes-music-all-music"<?php wdes_music_display_none( $active, 'wdes-music-all-music' ); ?>>
        <?php do_action( 'wdes_music_admin_before_all_music' ); ?>
        <div class="rtm-option-wrapper">
            <h3 class="rtm-option-title">
                <span class="name"><?php _e( $name, WDES_MUSIC ); ?></span>
            </h3>
            <div class="global-name">
                <div class="wdes-music-field">
                    <p><span>
                    	<strong><?php _e( 'Post Per Page', WDES_MUSIC ); ?> :</strong>
                        <input type="number" name="rtmedia-options[wdes_music_per_page]" id="wdes_music_post_per_page" value="<?php echo wdes_music_option( 'wdes_music_per_page' ); ?>" placeholder="<?php echo wdes_music_option( 'general_perPageMedia' ); ?>" />
                 	</span></p>
                </div>
            </div>
        </div>
        </div>
        </div>
        <div id="wdes-music-all-music-shortcode">
        <h6 class="wdes-music-toggle" data-toggle="wdes-music-all-music-shortcode"><?php _e( "$name Shortcode", WDES_MUSIC ); ?></h6>
        <div class="wdes-music-group wdes-music-all-music-shortcode"<?php wdes_music_display_none( $active, 'wdes-music-all-music-shortcode' ); ?>>
        <?php do_action( 'wdes_music_admin_before_all_music' ); ?>
        <div class="rtm-option-wrapper">
            <h3 class="rtm-option-title">
                <span class="name"><?php _e( "Display by:", WDES_MUSIC ); ?></span>
                <?php wdes_music_tooltip( "This settings control the list of music displayed in your music shortcode. Either display all uploaded music, or display by user. Default is \"All\"." ); ?>
            </h3>
            <div class="global-name">
                <div class="wdes-music-field">
                	<p><?php _e( "Use <code>[wdes-rtmedia-music]</code> shortcode in custom pages." ); ?></p>
                    <p>
                        <select name="rtmedia-options[wdes_music_all_music_shortcode_by]" id="wdes_music_all_music_shortcode_by">
                            <option value=""<?php selected( '', wdes_music_option( 'wdes_music_all_music_shortcode_by' ) ); ?>><?php _e( "All uploaded music" ); ?></option>
                            <?php
                                foreach( wdes_music_get_all_users_id() as $user ){
                                    printf( '<option value="%s"%s>%s</option>', $user, selected( $user, wdes_music_option( 'wdes_music_all_music_shortcode_by' ), true ), wdes_music_get_user( 'display_name', $user ) );	
                                }
                            ?>
                        </select>
                    </p>
                </div>
            </div>
        </div>
        </div>
        </div>
		<div id="wdes-music-upload-music">
            <h6 class="wdes-music-toggle" data-toggle="wdes-music-upload-music"><?php _e( "Upload music", WDES_MUSIC ); ?></h6>
            <div class="wdes-music-group wdes-music-upload-music"<?php wdes_music_display_none( $active, 'wdes-music-upload-music' ); ?>>
                <div class="rtm-option-wrapper">
                    <h3 class="rtm-option-title"><span class="name"><?php _e( "$name upload music", WDES_MUSIC ); ?></span></h3>
                    <div class="global-name">
                        <div class="wdes-music-field">
                        <table class="form-table"  >
                          <tr>
                            <th><?php _e( "Enable view music button", WDES_MUSIC ); ?></th>
                            <td><fieldset>
                                <span class="rtm-field-wrap"><span class="rtm-form-checkbox" >
                                <label for="wdes_music_enable_music_button" class="switch">
                                  <input type="checkbox" data-toggle="switch" id="wdes_music_enable_music_button" name="rtmedia-options[wdes_music_enable_music_button]" value = "1" <?php checked( 1, wdes_music_option( 'wdes_music_enable_music_button' ) ); ?>>
                                  <span class="switch-label" data-on="On" data-off="Off"></span><span class="switch-handle"></span> </label>
                                </span></span> <span class="rtm-tooltip"> <i class="dashicons dashicons-info rtmicon"></i> <span class="rtm-tip"><?php _e( "Enable this option if you want to add a view music button on the upload page.", WDES_MUSIC ); ?></span> </span>
                              </fieldset></td>
                          </tr>
                        </table>
                            <p>
                                <label>
                                	<input type="text" name="rtmedia-options[wdes_music_view_music_button_url]" id="wdes_music_view_music_button_url" value="<?php echo wdes_music_option( 'wdes_music_view_music_button_url' ); ?>" /> <?php _e( 'View music button url.', WDES_MUSIC ); ?>
                            	</label>
                            </p>
                            <p>
                                <label>
                                	<input type="text" name="rtmedia-options[wdes_music_view_music_button_text]" id="wdes_music_view_music_button_text" value="<?php echo wdes_music_option( 'wdes_music_view_music_button_text' ); ?>" /> <?php _e( 'View music button text', WDES_MUSIC ); ?>
                            	</label>
                            </p>
                        <table class="form-table"  >
                          <tr>
                            <th><?php _e( "Cancel and Start Upload button", WDES_MUSIC ); ?></th>
                            <td><fieldset>
                                <span class="rtm-field-wrap"><span class="rtm-form-checkbox" >
                                <label for="wdes_music_cancel_start_upload_button" class="switch">
                                  <input type="checkbox" data-toggle="switch" id="wdes_music_cancel_start_upload_button" name="rtmedia-options[wdes_music_cancel_start_upload_button]" value = "1" <?php checked( 1, wdes_music_option( 'wdes_music_cancel_start_upload_button' ) ); ?>>
                                  <span class="switch-label" data-on="On" data-off="Off"></span><span class="switch-handle"></span> </label>
                                </span></span> <span class="rtm-tooltip"> <i class="dashicons dashicons-info rtmicon"></i> <span class="rtm-tip"><?php _e( "Enable this option if you want to add a Cancel and Start Upload button on the upload page. This will show after you have added a music file.", WDES_MUSIC ); ?></span> </span>
                              </fieldset></td>
                          </tr>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php do_action( 'wdes_music_admin_add_settings' ); ?>
    </div>
    <?php
}