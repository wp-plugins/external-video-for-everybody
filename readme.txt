=== External "Video for Everybody" ===
Contributors: kwiliarty
Donate link: none
Tags: video, html5, ogg, theora, flash, webm, vp8, videojs
Requires at least: 2.8
Tested up to: 4.2
Stable tag: 2.1.2

Delivers ogg/theora (and optional webm) html5 video from an external storage location with fallbacks to flash, and links for download.

== Description ==

**Important: This plugin is designed to operate on media files hosted 
outside your WordPress site. It does not integrate with the Media library, 
and it does not create the media files.**

*External "Video for Everybody"* is a WordPress plugin that you can use to show
videos on your WordPress site. You enter a simple shortcode on your page, and
the plugin generates the HTML to deliver the media. Browsers that understand
the HTML5 tag will display MPEG/H.264 (.mp4) files, VP8/webm (.webm), or Ogg/Theora (.ogv) files. Other browsers can use Flash to play the .mp4. In all cases, the
markup includes links to download the media files. The HTML comes with 
only minor variations straight from the [Video for
Everybody](http://camendesign.com/code/video_for_everybody) model. See that site for fuller details.

Users can optionally use the 
[VideoJS](http://videojs.com) JavaScript library to outfit their videos 
with an attractive set of controls that includes a full screen option. 

This plugin is not for everybody, even if the video tries to be. If I were not
writing my own plugin, I would probably be using the [Degradable HTML5 audio
and video Plugin](http://soukie.net/degradable-html5-audio-and-video-plugin/) by Pavel Soukenik. My plugin uses and will follow the [Video for
Everybody](http://camendesign.com/code/video_for_everybody)
approach. I also offer an options page where you can define site-wide default paths and dimensions. The defaults can be overridden in any particular shortcode, but if most of your video resides in the same place and has consistent dimensions, site-wide defaults keep the shortcodes simple. Soukenik's
shortcodes give you more control over playback options.

**Important:** Most autobuffering problems have been settled, but 
preloading (autobuffering) preferences are supported unevenly in some
older browers and versions of browsers. Depending on how many movies you 
serve on a single page, and on how large they are, autobuffering can 
significantly slow your clients' browsers, and it can also hit your bandwidth. 

For a discussion of autobuffering in html5 see:
http://daringfireball.net/2009/12/html5_video_unusable

== Installation ==

You can search for this plugin from your WordPress plugins administration interface and install it automatically.

Or to install it manually:

1. Download the zipped plugin using the link to the right
1. Unzip it and put the folder in your wp-contents/plugins folder

After activating the plugin, you should:

1. Configure the settings at *Media > External VfE*
1. Add shortcodes to your posts and pages in order to diplay video

== Frequently Asked Questions ==

= Why this plugin? =

I have developed this plugin to provide an easy way to implement the "Video for Everybody" approach on a WordPress site. 

I also wanted a way to update the delivery for all of my video files. As I update the plugin, existing video will be served using the new code.

Finally, I wanted to have a settings page so that I could spare myself the trouble of adding repetetive details to each video entry. 

= Who is this plugin for? =

Ultimately it's for myself. I created the External "Video for Everybody"
plugin to suit my own priorities, but I also tried to put it together in a way
that would make it useful for and usable by others, and I am happy to share
it. I host my video outside my WordPress site. I want to use HTML5, Ogg and Webm.
So... *External "Video for Everybody"*

= Are you offering support for this plugin? =

No.

= How can I create the video files this plugin looks for? =

The [Theora Cookbook](http://en.flossmanuals.net/TheoraCookbook/) has a lot of information on how to encode .ogv files.

The [Miro Video Converter](http://www.mirovideoconverter.com/) is a handy free and opensource utility for this purpose.

You might also be interested in trying a [shell
script](https://github.com/kwiliarty/vfe-sh) I use to convert videos
for myself. The shell script depends on your having certain command-line
tools installed on your computer: ffmpeg and qtfaststart.py in particular.

== Screenshots ==

1. External Video for Everybody options page
2. Drop shadow and gradient background for posterless videos in FireFox 4

== Upgrade Notice ==

= 2.1.2 =
Updates VideoJS library to 4.12.5

= 2.1.1 =
Updates VideoJS library to 4.7.3

= 2.1 =
Critical security update to VideoJS 4.0.2

= 2.0 =
Now includes an option to detect remote files and adjust source tags and downloads based on which files are actually available. Integrates VideoJS 3.2.0.

= 1.0 =
Now includes an optional built-in style sheet that is especially useful for
FireFox 4 if you do not use poster images.

= 0.9.1 =
Updates VideoJS library to 2.0.2

= 0.9 =
Updates to Video for Everybody v0.4.2 with better sizing for flash variant. Includes new option to use VideoJS library.

= 0.8 =
Adds Webm handling for video display and for the downloads list

= 0.7.1 =
Fixes a minor bug in the line that calls the poster image for the embedded Flash object.

= 0.7 =
This update follows Video for Everybody 0.4+ by dropping embedded QuickTime objects. The generated HTML is lighter, but it is also easier to prepare the media. This update and the previous one have reduced the number of files you need to create from 4 to 2. The user experience is also improved.

= 0.6 =
This update adds an option for omitting the poster attribute from the video tag.
Omitting the poster attribute is currently required for playback on iPads. 
This update also adds experimental support for links to external media that
require a query string after the file name. 

= 0.5 =
A small but important change to the code brings the plugin up to date with the official version 0.3.3 of Video for Everybody. The update is critical for playback on iPads.

= 0.4.6 =
This is a very minor upgrade with no consequences for functionality.

== Usage ==

A minimal shortcode takes the form

>`[external-vfe name="VIDEO"]`

where *VIDEO* stands for the simple name of your video (without any file
extension).

*name* is the only required attribute. Optional attributes are:

* width (in pixels)
* height (in pixels)
* path (URL to the folder where you host your video files)
* query (to follow the file name in the URL; should start with "?")
* include_poster (set to "true" to include a poster attribute in the video tag)
* webm_download (include a link to a webm file in the list of downloads)
* poster_extension (png, jpg, or gif)
* swf_file (The address to your Flash player)
* vjs (set to "true" to provide attractive video controls with full screen option)
* file_detector (try to detect remote files if "true")
* access_cookie (if you need a simple cookie to access the remote files)

All of the above can also be set as defaults on the options page under:
Media > External VfE

The options page also includes two parameters that cannot be overridden for 
an individual video: 

* the ability to disable VideoJS so that the JavaScript and the style sheet will not load at all 
* the option to disable the built-in style sheet

See the plugin home page for more details:
http://open.pages.kevinwiliarty.com/external-video-for-everybody/

== Changelog ==

= 2.1.2 =
* Updates VideoJS to 4.12.5

= 2.1.1 =
* Updates VideoJS to 4.7.3

= 2.1 =
* Critical security update to VideoJS 4.0.2

= 2.0 =
* Optional file detection will generate source tags and download links only for remote resources that are actually available. 
* File detection overrides webm_download settings
* Integrates VideoJS 3.2.0

= 1.0 =
* Brand new installations will include posters by default. Existing installations will retain existing poster preference.
* An optional built-in style sheet will add a drop-shadow and a background gradient so that videos without posters will have a visual presence in FireFox 4.

= 0.9.1 =
* Updates VideoJS library to 2.0.2
* Moves the download links outside the main video div so that they will not be hidden by VideoJS

= 0.9 =
* Follows Video for Everybody v0.4.2 adding a floating controlbar for the flash video so that the video itself can play at full size
* wraps each video element in a div.evfe
* Adds some additional CSS classes to internal elements
* Adds a global setting and shortcode option to skin videos with the default flavor of Steve Heffernan's VideoJS JavaScript library (with minor modification to ease the style integration with a wider variety of WordPress themes)
* Includes an option to disable VideoJS entirely so as to prevent the loading of unwanted JavaScript and CSS files

= 0.8 =
* Adds support for webm video playback
* Adds a global option as well as a shortcode attribute to include a .webm file in the automatically generated list of videos for download.

= 0.7.1 =
* Fixes a minor bug in the line that calls the poster image for the embedded Flash object. 

= 0.7 =
* drops embedded quicktime fallback. (Simplifies html; simplifies processing overhead; improves playback experience)
* adds commented attribution to Kroc Camen of Camen Design
* minor adjustments to video element attributes, including preload='none'
* adopted self-closing source elements to validate as html5

= 0.6 =
* adds an option to omit posters (as required for iPad playback)
* adds experimental support for query elements in URL's to media assets
* adds a missing period for embedded flash encoding

= 0.5 =
* .mp4 now listed as first source -- critical change for iPad

= 0.4.7 =
* factual corrections to readme.txt

= 0.4.6 =
* bundled with a better readme.txt

= 0.4.5 =
* Added some help tips to the options page

= 0.4.4 =
* Added a *class="external-vfe"* attribute to the &lt;video> tag
to make it available for CSS styling

= 0.4.3 =
* Introduced *href* and *target* parameters to the QuickTime object to
prevent IE from autoloading entire movies.
* **IMPORTANT:** It is now necessary to upload a fourth (tiny) file for
each movie.

= 0.4.2 =
* prefixed *evfe_* to settings names make them distinctive
* **IMPORTANT:** Old settings need to be re-entered on the *Media >
External-VfE* settings page

= 0.4.1 =
* updated to Video for Everybody 0.3.2

= 0.4 =
* initial working version

