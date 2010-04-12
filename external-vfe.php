<?php
/*
Plugin Name: External "Video for Everybody"
Plugin URI: http://open.pages.kevinwiliarty.com/external-video-for-everybody/
Description: Use the "Video for Everybody" code (v0.3.2--http://camendesign.com/code/video_for_everybody) to display ogg/theora video on browsers that support the html5 &lt;video&gt; tag while falling back to Quicktime or Flash on browsers that do not.
Version: 0.5
Author: Kevin Wiliarty
Author URI: http://open.pages.kevinwiliarty.com/
*/

/* 
Copyright 2010  Kevin Wiliarty (email: kevin.wiliarty@gmail.com)

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


//whitelist and sanitize file extension for poster images
function sanitize_image_extension( $input ) {
	//array of allowed extensions
	$extensions = array( 'jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF' );
	if ( !( in_array( $input, $extensions ))) { //if the input is not in array
		$input = 'jpg'; //revert to jpg
	}
	return $input; //return the validated (and sanitized) value
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
	//evfe_path must be url
	register_setting( 'external-vfe-group', 'evfe_path', 'esc_url' );
	//evfe_height must be int
	register_setting( 'external-vfe-group', 'evfe_height', 'intval' );
	//evfe_width must be int
	register_setting( 'external-vfe-group', 'evfe_width', 'intval' );
	//poster image file extension must be whitelisted
	register_setting(
		'external-vfe-group', 
		'evfe_poster_extension', 
		'sanitize_image_extension'
	);
	//path to flash player must be url
	register_setting( 'external-vfe-group', 'evfe_swf_file', 'esc_url' );
}

//function to create options page
function external_vfe_options() {
	?>
	<div class='wrap'>
		<h2>External VfE Settings</h2>
		<form method='post' action='options.php'>
			<?php settings_fields( 'external-vfe-group' ); ?>
			<table class='form-table' style='width:800px;'>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>path:</th>
					<td><input style='width: 300px;' type='text' name='evfe_path' value='<?php echo get_option( 'evfe_path' ); ?>' /></td>
					<td>URL to the directory that contains your media files. Include the trailing slash.</td>
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
					<th scope='row' style='text-align:right;'>poster_extension:</th>
					<td><input type='text' name='evfe_poster_extension' value='<?php echo get_option( 'evfe_poster_extension' ); ?>' /></td>
					<td>Default file extension for your poster images. Permissible options are: <em>jpg</em>, <em>png</em>, <em>gif</em>, <em>JPG</em>, <em>PNG</em>, <em>GIF</em>. Impermissible options revert to <em>jpg</em></td>
				</tr>
				<tr valign='top'>
					<th scope='row' style='text-align:right;'>swf_file:</th>
					<td><input style='width:auto;' type='text' name='evfe_swf_file' value='<?php echo get_option( 'evfe_swf_file' ); ?>' /></td>
					<td>URL for a flash player. (You can get a copy of the <a target='_blank' href='http://www.longtailvideo.com/players/jw-flv-player/'>JW Player</a> and put it somewhere on your site, for instance.)</td>
				</tr>
			</table>
			<p class='submit'>
				<input type='submit' class='button-primary' value='<?php _e( 'Save Changes' ); ?>' />
			</p>
		</form>
	</div>
<?php }

//function to parse shortcode and render video
function external_vfe_func( $atts ) {

	//parse the shortcode attributes
	extract(
		shortcode_atts(
			array(
				'path' => get_option( 'evfe_path' ),
				'poster_extension' => get_option( 'evfe_poster_extension' ),
				'swf_file' => get_option( 'evfe_swf_file' ),
				'name' => 'no name',
				'height' => get_option( 'evfe_height' ),
				'width' => get_option( 'evfe_width' ),
			), 
			$atts
		)
	);

	//set height for quicktime with room for controls
	$qt_height = $height+16;

	//if a value for name has been provided
	if ( $name != 'no name' ) {
		//render the html to display the video
		return "
			<video class='external-vfe' width='{$width}' height='{$height}' poster='{$path}{$name}.{$poster_extension}' controls='controls'>
				<source src='{$path}{$name}.mp4' type='video/mp4'></source>
				<source src='{$path}{$name}.ogv' type='video/ogg'></source><!--[if gt IE 6]>
				<object width='{$width}' height='{$qt_height}' classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B'><!
				[endif]--><!--[if !IE]><!-->
				<object width='{$width}' height='{$qt_height}' type='video/quicktime' data='{$path}{$name}.mp4'>
				<!--<![endif]-->
				<param name='src' value='{$path}{$name}-poster.mp4' />
				<param name='href' value='{$path}{$name}.mp4' />
				<param name='target' value='myself' />
				<param name='showlogo' value='false' />
				<param name='autoplay' value='false' />
				<object width='{$width}' height='{$height}' type='application/x-shockwave-flash'
					data='{$swf_file}?image={$path}{$name}.{$poster_extension}&file={$path}{$name}.mp4'>
					<param name='autoplay' value='false' />
					<param name='movie' value='{$swf_file}?image={$path}{$name}{$poster_extension}&file={$path}{$name}.mp4' />
					<img src='{$path}{$name}.{$poster_extension}' width='{$width}' height='{$height}' alt='Video here'
						 title='No video playback capabilities, please download the video below' />
				</object><!--[if gt IE 6]><!--></object><!--<![endif]-->
			</video>
			<p>Downloads: <br />
			<a href='{$path}{$name}.mp4'>{$path}{$name}.mp4</a><br />
			<a href='{$path}{$name}.ogv'>{$path}{$name}.ogv</a>
			</p>
		";
	}
}

//add the shortcode to listen for
add_shortcode( 'external-vfe', 'external_vfe_func' );
?>
