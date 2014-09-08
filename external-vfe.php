<?php
/*
Plugin Name: External "Video for Everybody"
Plugin URI: http://open.pages.kevinwiliarty.com/external-video-for-everybody/
Description: Use the "Video for Everybody" code (v0.4.1--http://camendesign.com/code/video_for_everybody) to display ogg/theora, MPEG/h.264 or VP8/webm video on browsers that support the html5 &lt;video&gt; tag while falling back to Flash (h.264) on browsers that do not.
Version: 2.1.1
Author: Kevin Wiliarty
Author URI: http://open.pages.kevinwiliarty.com/
*/

/* 
Copyright 2012  Kevin Wiliarty (email: kevin.wiliarty@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//load the VideoJS library only if not disabled
if ( get_option( 'evfe_disable_vjs' ) != "true" ) {

	//register and enqueue the video-js style sheet
	function evfe_video_js_css() {
		//set up the path variables
		$plugin_name = basename(__FILE__);
		$base_folder = str_replace( $plugin_name, "", plugin_basename(__FILE__));
		$plugin_folder = WP_PLUGIN_URL . '/' . $base_folder;
		$video_js_css_file = $plugin_folder . 'video-js/video-js.css';
		wp_register_style( 'evfe-video-js' , $video_js_css_file );
		wp_enqueue_style( 'evfe-video-js' );
	}

	add_action( 'init' , 'evfe_video_js_css' );

	//stage the loading of the javascript files
	function evfe_init() {
		//set up the path variables
		$plugin_name = basename(__FILE__);
		$base_folder = str_replace( $plugin_name, "", plugin_basename(__FILE__));
		$plugin_folder = WP_PLUGIN_URL . '/' . $base_folder;
		
		//register video.js
		wp_register_script( 'video-js' , $plugin_folder . 'video-js/video.js' );

		//enqueue video.js
		if (!is_admin()) {
			wp_enqueue_script(
				'video-js' ,
				$plugin_folder . 'video-js/video.js' 
			);
		}
		
		//register evfe-helper.js
		wp_register_script( 'evfe-helper' , $plugin_folder . 'evfe-helper.js' );

		//enqueue evfe-helper.js
		if (!is_admin()) {
			wp_enqueue_script( 
				'evfe-helper' , 
				$plugin_folder . 'evfe-helper.js' ,
				array( 'jquery' , 'video-js' ) 
			);
		}
	}

	//hook the init function
	add_action( 'init' , 'evfe_init' );

}

//function to register and enqueue evfe stylesheet
function evfe_css () {
	//if the built-in style sheet not disabled and this is not an admin page
	if ( get_option( 'evfe_disable_css' ) != 'true' && !is_admin() ) {
		//register and enqueue the evfe style sheet
		$evfe_css = plugins_url( '/evfe.css' , (__FILE__) );
		wp_register_style( 'evfe-css' , $evfe_css );
		wp_enqueue_style( 'evfe-css' );
	}
}

//add the evfe_css function to the init hook
add_action( 'init' , 'evfe_css' );

//whitelist and sanitize file extension for poster images
function sanitize_image_extension( $input ) {
	//array of allowed extensions
	$extensions = array( 'jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF' );
	if ( !( in_array( $input, $extensions ))) { //if the input is not in array
		$input = 'jpg'; //revert to jpg
	}
	return $input; //return the validated (and sanitized) value
}

//sanitize query string
function sanitize_query_string( $untrusted ) {
	$trusted = preg_replace( '/[^a-z0-9?&=;\-~_+%\.]/i' , "" , $untrusted );
	return $trusted;
}

//sanitize checkboxes
function sanitize_checkbox( $checkbox ) {
	if ( $checkbox != true ) {
		$checkbox = "false";
	}
	return $checkbox;
}

//hook for custom plugin settings menu
add_action( 'admin_menu', 'external_vfe_menu' );

//function to create the custom settings menu
function external_vfe_menu() {
	add_submenu_page(
		'upload.php', //adds submenu to "Media" 
		'External VfE Options', //html <title> for page 
		'External VfE', //title for menu listing 
		'administrator', //capability required for access 
		__FILE__, //this file handles display of menu page
		'external_vfe_options' //the function that displays the settings page
	);
	//call register settings function
	add_action( 'admin_init', 'register_external_vfe_settings' );
}

//function to register settings
function register_external_vfe_settings() {
	
	//is this a fresh installation?
	if (!(get_option('evfe_include_poster'))) {$fresh_install = true;}

	//evfe_path must be url
	register_setting( 'external-vfe-group', 'evfe_path', 'esc_url' );

	//evfe_height must be int
	register_setting( 'external-vfe-group', 'evfe_height', 'intval' );

	//evfe_width must be int
	register_setting( 'external-vfe-group', 'evfe_width', 'intval' );

	//whether to include posters in the code
	register_setting( 'external-vfe-group', 'evfe_include_poster' , 'sanitize_checkbox' );

	//poster image file extension must be whitelisted
	register_setting(
		'external-vfe-group', 
		'evfe_poster_extension', 
		'sanitize_image_extension'
	);

	//path to flash player must be url
	register_setting( 'external-vfe-group', 'evfe_swf_file', 'esc_url' );

	//query portion of links to media assets must be url safe
	register_setting( 'external-vfe-group', 'evfe_query', 'sanitize_query_string' );

	// file detector will use a checkbox
	register_setting( 'external-vfe-group' , 'evfe_file_detector' , 'sanitize_checkbox' );

	// cookie string, validate as a query string (maybe too restrictive)
	register_setting( 'external-vfe-group' , 'evfe_access_cookie' , 'sanitize_query_string' );
	
	//include webm file in the list of downloads
	register_setting( 'external-vfe-group' , 'evfe_webm_download' , 'sanitize_checkbox' );
	
	//use VideoJS controls by default
	register_setting( 'external-vfe-group' , 'evfe_vjs_default' , 'sanitize_checkbox' );

	//disable VideoJS to prevent loading of JavaScript and style sheet
	register_setting( 'external-vfe-group' , 'evfe_disable_vjs' , 'sanitize_checkbox' );	
	//disable built-in style sheet
	register_setting( 'external-vfe-group' , 'evfe_disable_css' , 'sanitize_checkbox' );

	//set initial values
	if ($fresh_install == true) {
		update_option( 'evfe_include_poster' , 'true' );
	}

}

//function to create options page
function external_vfe_options() {
	?>
	<div class='wrap'>
		<h2>External VfE Settings</h2>
		<form method='post' action='options.php'>
			<?php settings_fields( 'external-vfe-group' ); ?>
			<table class='form-table' style='width:95%;'>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>path:</th>
					<td><input style='width: 300px;' type='text' name='evfe_path' value='<?php echo get_option( 'evfe_path' ); ?>' /></td>
					<td>URL to the directory that contains your media files. Include the trailing slash.</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>query:</th>
					<td><input style='width: 300px;' type='text' name='evfe_query' value='<?php echo get_option( 'evfe_query' ); ?>' /></td>
					<td>(Experimental!) An optional query string to follow the file name. Should begin with a question mark. Most users will not need this.</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>width:</th>
					<td><input type='text' name='evfe_width' value='<?php echo get_option( 'evfe_width' ); ?>' /></td>
					<td>Default width (in pixels) for your videos. (Max for iPhone is 640.)</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>height:</th>
					<td><input type='text' name='evfe_height' value='<?php echo get_option( 'evfe_height' ); ?>' /></td>
					<td>Default height (in pixels) for your videos. (Max for iPhone is 480.)</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>include_poster:</th>
					<td><input type='checkbox' name='evfe_include_poster' value='true' <?php if ( get_option( 'evfe_include_poster' ) == "true" ) {echo "checked='yes' ";} ?>/></td>
					<td>Posters must be disabled to allow iPad/iPod playback in iOS3, but the problem has been corrected since iOS4.</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>webm_download:</th>
					<td><input type='checkbox' name='evfe_webm_download' value='true' <?php if ( get_option( 'evfe_webm_download' ) == "true" ) {echo "checked='yes' ";} ?>/></td>
					<td>Check the box to include a webm file in the downloads list. This setting will be ignored if file detection is used.</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>file_detector:</th>
					<td><input type='checkbox' name='evfe_file_detector' value='true' <?php if ( get_option( 'evfe_file_detector' ) == "true" ) {echo "checked='yes' ";} ?>/></td>
					<td>The file detector tests whether external video resources exist before including them in the list of sources and downloads. Using this feature can slow loading times. If you always provide all file types (.mp4, .webm, .ogv), then you do not need this feature. Likewise, if you are not using the VideoJS option, then you do not strictly need this feature, either, though it will help the plugin to be smart about which downloads to offer. If your videos are not public, then you definitely should not use this option unless you can set a simple cookie that will allow your WordPress host to access the videos.</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>access_cookie:</th>
					<td><input type='text' name='evfe_access_cookie' value='<?php echo get_option( 'evfe_access_cookie' ); ?>' /></td>
					<td>You may be able to use file detection on files that are not fully public. If you know a cookie string that will get you in, enter it here. Relevant only under very narrow circumstances. </td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>poster_extension:</th>
					<td><input type='text' name='evfe_poster_extension' value='<?php echo get_option( 'evfe_poster_extension' ); ?>' /></td>
					<td>Default file extension for your poster images. Permissible options are: <em>jpg</em>, <em>png</em>, <em>gif</em>, <em>JPG</em>, <em>PNG</em>, <em>GIF</em>. Impermissible options revert to <em>jpg</em></td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>swf_file:</th>
					<td><input style='width:auto;' type='text' name='evfe_swf_file' value='<?php echo get_option( 'evfe_swf_file' ); ?>' /></td>
					<td>URL for a flash player. (You can get a copy of the <a target='_blank' href='http://www.longtailvideo.com/players/jw-flv-player/'>JW Player</a> and put it somewhere on your site, for instance.)</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>vjs_default:</th>
					<td><input type='checkbox' name='evfe_vjs_default' value='true' <?php if ( get_option( 'evfe_vjs_default' ) == "true" ) {echo "checked='yes' ";} ?>/></td>
					<td>Use <a href='http://videojs.com' target='_blank'>VideoJS</a> to provide custom controls by default.</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>disable_vjs:</th>
					<td><input type='checkbox' name='evfe_disable_vjs' value='true' <?php if ( get_option( 'evfe_disable_vjs' ) == "true" ) {echo "checked='yes' ";} ?>/></td>
					<td>Checking this box will disable the VideoJS library entirely and prevent the loading of the associated JavaScript and CSS files.</td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>disable_css:</th>
					<td><input type='checkbox' name='evfe_disable_css' value='true' <?php if ( get_option( 'evfe_disable_css' ) == "true" ) {echo "checked='yes' ";} ?>/></td>
					<td>Checking this box will disable the plugin's built-in style sheet.</td>
				</tr>
			</table>
			<p class='submit'>
				<input type='submit' class='button-primary' value='<?php _e( 'Save Changes' ); ?>' />
			</p>
		</form>
	</div>
<?php }

// function to test whether file exists
function check_remote_source( $url , $cookie='' ) {

	$ch = curl_init();
	curl_setopt( $ch , CURLOPT_URL , $url );
	curl_setopt( $ch , CURLOPT_HEADER , 1 );
	curl_setopt( $ch , CURLOPT_NOBODY , 1 );
	curl_setopt( $ch , CURLOPT_FAILONERROR , 1 );
	curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
	curl_setopt( $ch , CURLOPT_COOKIE , $cookie );
	curl_setopt( $ch , CURLOPT_CONNECTTIMEOUT , 1 );
	$file_exists = curl_exec($ch);
	curl_close($ch);
		
	if ( $file_exists !== FALSE ) { return TRUE; }
	else { return FALSE; }

}

//function to parse shortcode and render video
function external_vfe_func( $atts ) {

	//parse the shortcode attributes
	extract(
		shortcode_atts(
			array(
				'path' => get_option( 'evfe_path' ),
				'query' => get_option( 'evfe_query' ),
				'poster_extension' => get_option( 'evfe_poster_extension' ),
				'swf_file' => get_option( 'evfe_swf_file' ),
				'name' => 'no name',
				'height' => get_option( 'evfe_height' ),
				'width' => get_option( 'evfe_width' ),
				'include_poster' => get_option( 'evfe_include_poster' ),
				'webm_download' => get_option( 'evfe_webm_download' ),
				'file_detector' => get_option( 'evfe_file_detector' ),
				'access_cookie' => get_option( 'evfe_access_cookie' ),
				'vjs' => get_option( 'evfe_vjs_default' ),
			), 
			$atts
		)
	);

	//include poster or not
	$poster = "";
	if ( $include_poster == "true" ) {
		$poster = "poster='{$path}{$name}.{$poster_extension}{$query}'";
	}

	//use VideoJS to style the video controls
	$vjs_div = "";
	$vjs_video = "";
	$vjs_data = "";
	if ( $vjs == "true" ) {
		$vjs_div = " video-js-box";
		$vjs_video = " video-js vjs-default-skin";
		$vjs_data = " data-setup='{}'";
	}	

	// construct URL's and <source> tags
	$types = array ( 'mp4'=>'mp4' , 'webm'=>'webm' , 'ogv'=>'ogg' );
	foreach ( $types as $ext => $type ) {

		$url[$type] = "{$path}{$name}.{$ext}{$query}";
		
		// if using file detector
		if ( $file_detector == "true" ) {
			// set source and download to null
			$source[$type] = "";
			$download[$type] = "";
			// if the file does not exist
			if ( !(check_remote_source($url[$type],$access_cookie)) ) {
				// go to the next type
				continue;
			}
		}

		// otherwise build out the source and download strings
		$source[$type] = "\n<source src='{$url[$type]}' type='video/{$type}' />";
		$download[$type] = "<a class='{$type}-link' href='{$path}{$name}.{$ext}{$query}'>{$path}{$name}.{$ext}{$query}</a><br />";
	}

	// if not using file detection AND webm_download is false
	if ( ( $file_detector == "false" ) && ( $webm_download == "false" ) ) {
		// set the source and download for webm back to empty
		$source['webm'] = '';
		$download['webm'] = '';
	}

	// create the track tag
	// at the moment tracks are unsupported, even with VideoJS
	// because VideoJS uses an AJAX call which will not work across domains
	// $track_tag = "\n<track kind='captions' src='{$path}{$name}.vtt{$query}' default srclang='en' label='English' />";

	//if a value for name has been provided
	if ( $name != 'no name' ) {
		//render the html to display the video
		return "
			<div class='evfe'>
			<!-- ================================================ -->
			<!-- based on 'Video for Everybody' v0.4.2 by Kroc Camen of Camen Design -->
			<!-- <camendesign.com/code/video_for_everybody> -->
			<!-- ================================================ -->
			<video id='evfe-{$name}' class='external-vfe{$vjs_video}' width='{$width}' height='{$height}' {$poster} controls preload='none'{$vjs_data}>{$source['mp4']}{$source['webm']}{$source['ogg']}
				<object width='{$width}' height='{$height}' type='application/x-shockwave-flash' data='{$swf_file}'>
					<param name='movie' value='{$swf_file}' />
					<param name='flashvars' value='controlbar=over&amp;image={$path}{$name}.{$poster_extension}{$query}&amp;file={$path}{$name}.mp4{$query}' />
					<img src='{$path}{$name}.{$poster_extension}{$query}' width='{$width}' height='{$height}' alt='movie: {$name}'
						 title='No video playback capabilities, please download the video below' />
				</object>
			</video>
			</div>
			<p class='external-vfe-downloads'>Downloads: <br />
			{$download['mp4']}
			{$download['webm']}
			{$download['ogg']}
			</p>
		";
	}
}

//add the shortcode to listen for
add_shortcode( 'external-vfe', 'external_vfe_func' );
?>
