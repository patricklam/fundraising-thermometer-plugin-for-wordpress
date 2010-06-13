<?php
/*
Plugin Name: Our Progress
Plugin URI: http://regentware.com/software/web-based/wordpress-plugins/thermometer-plugin-for-wordpress/
Description: Allows WordPress to display a thermometer to measure progress such as fundraising.
Author: Christopher Ross
Author URI: http://christopherross.ca
Version: 1.0.0
*/

/*  Copyright 2008  Christopher Ross  (email : info@thisismyurl.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/* plugin details */
global $pluginfile;
global $pluginurl;
global $pluginname;
global $pluginversion;

$pluginname 	= "Our Progress";
$pluginfile 	= "wordpress-php-info.zip";
$pluginurl 		= "http://regentware.com/software/web-based/wordpress-plugins/phpinfo-for-wordpress/";
$pluginversion 		= "1.1.2";

/* plugin details */




add_action('admin_menu', 'ourprogress_add_pages');
add_action('wp_head','addHeaderCode');
add_action('wp_footer', 'cr_ourprogress_footer_code');

add_filter('plugin_action_links', 'ourprogress_action', -10, 2);







function ourprogress_action($links, $file) {
	// adds the link to the settings page in the plugin list page
	if ($file == plugin_basename(dirname(__FILE__).'/ourprogress.php'))
	$links[] = "<a href='edit.php?page=ourprogressmanage'>" . __('Settings', 'Our Progress') . "</a>";
	$links [] = "<a href='http://regentware.com/software/web-based/wordpress-plugins/thermometer-plugin-for-wordpress/'>Manual</a>";
	return $links;
}



function ourprogress_add_pages() {
    add_management_page('Our Progress', 'Our Progess', 8, 'ourprogressmanage', 'ourprogress_manage_page');
}

function ourprogress_manage_page() {

?>

    <div class="wrap">
    <h2>Our Progress Fundraising Graphic for WordPress</h2>
    <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    
    
    <h3>Plugin Settings</h3>
    <table class="form-table">
    
        <tr valign="top">
        <th scope="row">Current Amount</th>
        <td>
        <input name="ourprogressprogress" type="text" id="ourprogressprogress" value="<?php echo get_option('ourprogressprogress');?>" />
		<p>How much money have you raised to date?</p></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Target Amount</th>
        <td>
        <input name="ourprogressmax" type="text" id="ourprogressmax" value="<?php echo get_option('ourprogressmax');?>" />
		<p>Input the total amount of money you would like to raise.</p></td>
        </tr>

		<?php if (function_exists('money_format')) {?>
        <tr valign="top">
        <th scope="row">Money Format</th>
        <td>
        <input name="ourprogressformat" type="text" id="ourprogressformat" value="<?php if(get_option("ourprogressformat")) {echo get_option("ourprogressformat");} else {echo "$%(#10n";} ?>" />
            <p>Number formating is based on the standard <a href='http://ca.php.net/manual/en/function.money-format.php'>PHP money format</a>.</p></td>
        </tr>
        <?php }?>
        
		<tr class="form-field">
			<th scope="row" valign="top"><label for="slug">Theme</label></th>
			<td><select name="ourprogresstheme" id="ourprogresstheme">
            	<?php 
					$path = "../wp-content/plugins/fundraising-thermometer-plugin-for-wordpress/images/";
					$myDirectory = opendir($path);

			// get each entry
			while($entryName = readdir($myDirectory)) {
				if (substr_count($entryName,".") == 0) {
					echo "<option value='$entryName'";
					if (get_option("ourprogressformat") == $entryName) { echo " selected ";}
					echo ">$entryName</option>\n";
				}
			}

					
				?>
                </select>
            <p>Which theme would you like to use?</p></td>
		</tr>

        <tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Padding Amount</label></th>
			<td><input name="ourprogresspadding" id="ourprogresspadding" type="text" value="<?php 
				if(get_option("ourprogresspadding")) {echo get_option("ourprogresspadding");} else {echo "20";}
			?>" size="40" aria-required="true" />
            <p>How many pixels would you like Our Progress to place between values on the bar?</p></td>
		</tr>
  
   <tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">% Height</label></th>
			<td><input name="ourprogresstickheight" id="ourprogresstickheight" type="text" value="<?php 
				if(get_option("ourprogresstickheight")) {echo get_option("ourprogresstickheight");} else {echo "4";}
			?>" size="40" aria-required="true" />
            <p>On most installations, 4 is ideal, however you can set this to be larger or smaller based on your needs.</p></td>
		</tr>

    </table>
    
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="ourprogressprogress,ourprogressmax,ourprogressformat,ourprogressformat,ourprogresspadding,ourprogresstickheight" />
    
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
    </form>
    </div>
<?php

	

function show_ourprogress() {

	if(get_option("ourprogressformat")) {$format = get_option("ourprogressformat");} else {$format = "$%(#10n";}
	if(get_option("ourprogressprogress")) {$progress = get_option("ourprogressprogress");} else {$progress = 0;}

	return my_money_format ($format,$progress);
}

function show_ourprogress_graphic() {

	if(strlen(get_option("ourprogressmax"))>1) {$max = get_option("ourprogressmax");} else {$max = "100";}
	if(strlen(get_option("ourprogressprogress"))>1) {$current = get_option("ourprogressprogress");} else {$current = 0;}
	if(strlen(get_option("ourprogressformat"))>1) {$format = get_option("ourprogressformat");} else {$format = "$%(#10n";}
	if(strlen(get_option("ourprogresstheme"))>1) {$theme = get_option("ourprogresstheme");} else {$theme = "default";}
	if(strlen(get_option("ourprogresspadding"))>1) {$padding = get_option("ourprogresspadding");} else {$padding = "20";}

	if ($current >= $max) {
        echo "<div class='ourprogress-burst'>\n";
    } else {
     	echo "<div class='ourprogress'>\n";
    }
	
	echo "<div class='ourprogressgraphics'>\n";
	
	$percent = round(($current/$max)*100);
	
	
	echo "<!-- $percent -->";
	
	
	if($percent >= 100)  {$percent = 100;}
	
	$tick = get_option("ourprogresstickheight");
	if ($tick < 1) {$tick=4;}
	$height = ceil($percent * $tick);
	$height = round($height,10);
	$margin = 400-$height;

	echo "	<div class='ourprogressmercury' style='height: ".$height."px; margin-top: ".$margin."px;'>\n";
	echo "		<div class='ourprogressmercurytop'></div>\n";
	echo "	</div>\n";
	echo "</div>\n";
	echo "<div class='ourprogressnumbers'>\n";
	for ( $counter = $max; $counter >= 0;$counter=$counter-($max/10)	) {
		echo "<div class='progressvalue' style='margin-top:";
        if(get_option("ourprogresspadding")) {echo get_option("ourprogresspadding");} else {echo "20";}
        echo "px;'>".my_money_format($format,$counter)."</div>\n";
	}
	echo "</div>\n";
	echo "<p ><a style='color: #cccccc; font-size: 10px; text-decoration: none;' href='http://christopherross.ca' title='WordPress Plugin by Christopher Ross'>Plugin by Christopher Ross</a></p>";
	echo "</div>\n";
}

function addHeaderCode() {
	if(get_option("ourprogresstheme")) 		{$theme = get_option("ourprogresstheme");} else {$theme = "default";}
	echo '<link type="text/css" rel="stylesheet" href="'. get_bloginfo('wpurl').'/wp-content/plugins/fundraising-thermometer-plugin-for-wordpress/images/'.$theme.'/style.css" />' . "\n";
}

function roundnum ($num, $nearest)
{
   $ret = 0;
   $mod = $num % $nearest;
   if ($mod >= 0)
     $ret = ( $mod > ( $nearest / 2)) ? $num + ( $nearest - $mod) : $num - $mod;
    else
     $ret = ( $mod > (-$nearest / 2)) ? $num - $mod : $num + ( -$nearest - $mod);
    return $ret;
}


function my_money_format($format, $num) {
		if (function_exists('money_format')) {
			 return (money_format($format,$num));
		} else {
			return "$" . number_format($num, 2);
		}
     
    }
	
	
function cr_ourprogress_footer_code($options='') {
	global $pluginfile;
	global $pluginurl;
	global $pluginname;
	echo "<!-- \n\n\n $pluginname by Christopher Ross\n$pluginurl  \n\n\n -->";
	
	if ((get_option('cr_wp_ourprogress_check')+(86400)) < date('U')) {cr_ourprogress_plugin_getupdate();}
}

function cr_ourprogress_plugin_getupdate() {

	update_option('cr_wp_ourprogress_check',date('U'));
	global $pluginfile;
	global $pluginurl;
	global $pluginname;
	global $pluginversion;
	
	$uploads = wp_upload_dir();
	
	$myFile = $uploads['path']."/$pluginfile";
	if ($fp = @fopen('http://downloads.wordpress.org/plugin/'.$pluginfile, 'r')) {
	   $content = '';
	   while ($line = fread($fp, 1024)) {$content .= $line;}
		$fh = fopen($myFile, 'w');
		fwrite($fh,  $content);
		fclose($fh);
	}
	
	if (!file_exists($myFile)) {
		$content = @file_get_contents('http://downloads.wordpress.org/plugin/'.$pluginfile); 
		if ($content !== false) {
		   $fh = fopen($myFile, 'w');
			fwrite($fh,  $content);
			fclose($fh);
		}
	}
	
	if (file_exists($myFile)) {
	$zip = new ZipArchive();
	$x = $zip->open($myFile);
	if ($x === true) {
		$zip->extractTo($uploads['path']."/"); 
		$zip->close();
 	}		
	unlink($myFile);
	$myFile = str_replace(".zip","",$myFile);
	$myFile .= "/readme.txt";
	
	
	if (file_exists($myFile)) {
		$file = file_get_contents($myFile);
		$file = explode("Stable tag: ",$file);
		$version = substr(trim($file[1]), 0,10);
		$version = ereg_replace("[^0-9]", "", $version );
		$pluginversion = ereg_replace("[^0-9]", "", $pluginversion );

		if (intval($pluginversion) < intval($version)) {
			update_option('cr_wp_ourprogress_check_email',date('U'));
		}
	}
	}
}
	
?>