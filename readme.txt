=== External "Video for Everybody" ===
Contributors: kwiliarty
Donate link: none
Tags: video, html5, ogg, theora, flash
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 0.7

Delivers ogg/theora html5 video from an external storage location with fallbacks to flash, and links for download.

== Description ==

**Important: This plugin is designed to operate on media files hosted 
outside your WordPress site. It does not integrate with the Media library, 
and it does not create the media files.**

*External "Video for Everybody"* is a WordPress plugin that you can use to show
videos on your WordPress site. You enter a simple shortcode on your page, and
the plugin generates the HTML to deliver the media. Browsers that understand
the HTML5 tag will display MPEG/H.264 (.mp4) files or Ogg/Theora (.ogv) files. Other browsers can use Flash to play the .mp4. In all cases, the
markup includes links to download the media files. The HTML used here diverges
only slightly from the [Video for
Everybody](http://camendesign.com/code/video_for_everybody) model. See that site for fuller details.

* I add a class attribute to the tag to make it available for CSS styling
* I have not yet incorporated WebM support

This plugin is not for everybody, even if the video tries to be. If I were not
writing my own plugin, I would probably be using the [Degradable HTML5 audio
and video Plugin](http://soukie.net/degradable-html5-audio-and-video-plugin/) by Pavel Soukenik. My plugin uses and will follow the [Video for
Everybody](http://camendesign.com/code/video_for_everybody)
approach. I also offer an options page where you can define site-wide default paths and dimensions. The defaults can be overridden in any particular shortcode, but if most of your video resides in the same place and has consistent dimensions, site-wide defaults keep the shortcodes simple. Soukenik's
shortcodes give you more control over playback options.

**Important:** The Safari browser will autobuffer the media files you link to using this
plugin. Depending on how many movies you serve on a single page, and on how
large they are, autobuffering can significantly slow your clients' browsers,
and it can also hit your bandwidth. Please bear this in mind when considering
use of this plugin.

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
it. I host my video outside my WordPress site. I want to use HTML5 and Ogg.
So... *External "Video for Everybody"*

= Are you offering support for this plugin? =

No.

= How can I create the video files this plugin looks for? =

The [Theora Cookbook](http://en.flossmanuals.net/TheoraCookbook/) has a lot of information on how to encode .ogv files.

The [Miro Video Converter](http://www.mirovideoconverter.com/) is a handy free and opensource utility for this purpose.

You might also be interested in trying a [shell
script](http://kevinwiliarty.com/dokuwiki/doku.php/open/vfe_bash_script) I use to convert videos
for myself.

== Screenshots ==

1. options-page.png

== Upgrade Notice ==

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
* poster_extension (png, jpg, or gif)
* swf_file (The address to your Flash player)

All of these can also be set as defaults.

See the plugin home page for more details:
http://open.pages.kevinwiliarty.com/external-video-for-everybody/

== Changelog ==

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

