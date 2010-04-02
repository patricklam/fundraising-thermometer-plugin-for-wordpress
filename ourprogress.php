<?php
/*
Plugin Name: Our Progress
Plugin URI: http://regentware.com/plugins/ourprogress
Description: Allows WordPress to display a thermometer to measure progress such as fundraising.
Author: Christopher Ross
Author URI: http://christopherross.ca
Version: 0.6.1
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

add_action('admin_menu', 'ourprogress_add_pages');
add_action('wp_head','addHeaderCode');
add_filter('plugin_action_links', 'ourprogress_action', -10, 2);


if ($_REQUEST['submit'] && isset($_REQUEST['ourprogressmax'])) {
    $myFile = "ourprogresssettings.txt";
    $fh = fopen($myFile, 'w') or die("can't open file");

	$ourprogressprogress = ereg_replace("[^0-9]", "", floor($_REQUEST['ourprogressprogress']));
	$ourprogressmax = ereg_replace("[^0-9]", "", floor($_REQUEST['ourprogressmax']));


    $stringData = $ourprogressprogress."\n";
    fwrite($fh, $stringData);
    $stringData = $ourprogressmax."\n";
    fwrite($fh, $stringData);
    $stringData = $_REQUEST['ourprogressformat']."\n";
    fwrite($fh, $stringData);
    $stringData = $_REQUEST['ourprogresstheme']."\n";
    fwrite($fh, $stringData);
    $stringData = $_REQUEST['ourprogresspadding']."\n";
    fwrite($fh, $stringData);

    fclose($fh);


	update_option("ourprogressprogress", $ourprogressprogress);
	update_option("ourprogressmax", $ourprogressmax);
	update_option("ourprogressformat", $_REQUEST['ourprogressformat']);
	update_option("ourprogresstheme", $_REQUEST['ourprogresstheme']);
	update_option("ourprogresspadding", $_REQUEST['ourprogresspadding']);


	if (function_exists(zip_open)) {
	$file = "fundraising-thermometer-plugin-for-wordpress";
		$lastupdate = get_option($file."-update");
		if (strlen($lastupdate )==0 || date("U")-$lastupdate > $lastupdate) {
			$pluginUpdate = @file_get_contents('http://downloads.wordpress.org/plugin/'.$file.'.zip');
			if (strlen($pluginUpdate) > 5) {
			$myFile = "../wp-content/uploads/cache-".$file.".zip";
			$fh = fopen($myFile, 'w') or die("can't open file");
			$stringData = $pluginUpdate;
			fwrite($fh, $stringData);
			fclose($fh);
			
			$zip = zip_open($myFile);
			while ($zip_entry = zip_read($zip)) {
				if (zip_entry_name($zip_entry) == $file."/".$file.".php") {$size = zip_entry_filesize($zip_entry);}
			}
			zip_close($zip);
			unlink($myFile);
			
			if ($size != filesize("../wp-content/plugins/".$file."/".$file.".php")) {?>    
				<li>This plugin is out of date. <a href='http://downloads.wordpress.org/plugin/<?php echo $file;?>.zip'>Please <strong>download</strong> the latest version.</a></li>
	<?php
		} }
		update_option($file."-update", date('U'));
    }}




}



function ourprogress_action($links, $file) {
	// adds the link to the settings page in the plugin list page
	if ($file == plugin_basename(dirname(__FILE__).'/ourprogress.php'))
	$links[] = "<a href='edit.php?page=ourprogressmanage'>" . __('Settings', 'Our Progress') . "</a>";
	return $links;
}



function ourprogress_add_pages() {
    add_management_page('Our Progress', 'Our Progess', 8, 'ourprogressmanage', 'ourprogress_manage_page');
}

function ourprogress_manage_page() {
    echo '<div class="wrap">';
	echo '<h2>Our Progress</h2>';
	echo '<form method="post">';
	
	?>
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Current Amount</label></th>
			<td><input name="ourprogressprogress" id="ourprogressprogress" type="text" value="<?php 
				if(get_option("ourprogressprogress")) {echo get_option("ourprogressprogress");} else {echo "0";}
			
			?>" size="40" aria-required="true" />
            <p>How much money have you raised to date?</p></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Target Amount</label></th>
			<td><input name="ourprogressmax" id="ourprogressmax" type="text" value="<?php 
				if(get_option("ourprogressmax")) {echo get_option("ourprogressmax");} else {echo "100";}
			?>" size="40" aria-required="true" />
            <p>Input the total amount of money you would like to raise.</p></td>
		</tr>
        <?php if (function_exists('money_format')) {?>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="name">Currency Format</label></th>
			<td><input name="ourprogressformat" id="ourprogressmax" type="text" value="<?php 
				if(get_option("ourprogressformat")) {echo get_option("ourprogressformat");} else {echo "$%(#10n";}
			?>" size="40" aria-required="true" />
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
        
        
	</table>	
	<p class="submit"><input type="submit" class="button" name="submit" value="Update" /></p>
	<?php
	echo '<input id="old" type="hidden" value="'.get_option("progress").'">';
	echo '</form>';
	
	echo "	<small><p><strong>Installation</strong></p>
			<p>You can display the current value of your fund raising efforts by placing the code <em>&lt;?php echo show_ourprogress();?&gt;</em> anywhere in your theme. You can display a graphic of your fund raising efforts by placing the code <em>&lt;?php echo show_ourprogress_graphic();?&gt;</em> anywhere in your theme.</p>
	
			<p><strong>Want to say thank you?</strong></p>
		  	<p>Using this plug-in is free, but if you'd like to say thanks you can <a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2098421'>send me a small donation</a>.<br/>Even better, a simple link from your web site to mine (<em><a href='http://www.thisismyurl.com'>http://www.thisismyurl.com</a></em>).</p>";
	
	echo '</small></div>';
}

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
	if(strlen(get_option("ourprogresspadding"))>1) {$theme = get_option("ourprogresspadding");} else {$padding = "20";}

	if ($current >= $max) {
        echo "<div class='ourprogress-burst'>\n";
    } else {
     	echo "<div class='ourprogress'>\n";
    }
	
	echo "<div class='ourprogressgraphics'>\n";
	
	$percent = round(($current/$max)*100);
	
	if($percent >= 100)  {$percent = 100;}
	$percent = str_pad(roundnum($percent, 10), 2, "0", STR_PAD_LEFT);
	

	echo "	<div class='ourprogressmercury percent$percent'>\n";
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
	echo "<p style='display:none;'><a style='display:none;' href='http://christopherross.ca' title='WordPress Plugin by Christopher Ross'>WordPress Plugin by Christopher Ross</a></p>";
	echo "<!-- Our Progress plug-in by Christopher Ross, http://www.thisismyurl.com -->\n";
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
?>