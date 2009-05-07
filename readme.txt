=== Plugin Name ===
Contributors: christopherross, c.bavota
Plugin URI: http://thisismyurl.com/wordpress/plugins/ourprogress
Tags: progress, counter, thermometer, fund, raising, fundraising
Requires at least: 2.0.0
Tested up to: 2.7.1
Stable tag: 0.5.0

Our Progress allows WordPress to display a thermometer to measure progress such as fundraising.

== Description ==

Our Progress is designed to be a simple method for fund raising activities to be tracked and reported on the live website.


== Installation ==

To install Our Progress, please upload the ourprogess folder to your plugins folder and active the plugin.

== Displaying the results ==

If you would like to display the current money raised, include the following code in your WordPress theme:

<?php echo show_ourprogress() ;?>

To display the graphic results, include the following code in your theme:

<?php echo show_ourprogress_graphic();?>


== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg

== Updates ==
Updates to the plugin will be posted here, to [thisismyurl](http://www.thisismyurl.com/plugins/ourprogress)

== Frequently Asked Questions ==

= How do I display the results? =

If you would like to display the current money raised, include the following code in your WordPress theme:

<?php echo show_ourprogress() ;?>

To display the graphic results, include the following code in your theme:

<?php echo show_ourprogress_graphic();?>

= Can I include it in a Widget? =

Yes! If you add a text widget to your sidebar, simply paste the above code into the text area and the plugin will work as a widget.

= Why can't I see any graphics? =

There are two common reasons graphics are not appearing.

Check that your wp-content/plugins/fundraising-thermometer-plugin-for-wordpress/ folder (and all subfolders) is readable (chmod 755).

Ensure the plugin folder is named fundraising-thermometer-plugin-for-wordpress, not ourprogress as in earlier versions.



= My local currency isn't displaying correctly! =

In some cases, local currency needs to be set in the code by adding the line setlocale(LC_MONETARY, Ôen_USÕ); immediately under the first */ for example:

Version: 0.2.5
*/
setlocale(LC_MONETARY, Ôen_USÕ);

= How do I format the numbers? =

The plugin uses standard php money_format(); formating.

= Is this plugin stable? =

Until I upgrade the version number to 1.x, I still consider this plugin to be under development but it has been tested and works well.

== Donations ==
If you would like to donate to help support future development of this tool, please visit [thisismyurl](http://www.thisismyurl.com/donations)


== Change Log ==

3.0 (2009-03-16)
Added the change log

