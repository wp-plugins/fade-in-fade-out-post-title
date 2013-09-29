<?php

/*
Plugin Name: Fade in fade out post title
Description: Fade in fade out post title, It is an excellent way to transition between two messages.
Author: Gopi.R
Version: 9.1
Plugin URI: http://www.gopiplus.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/
Author URI: http://www.gopiplus.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'FIFO_PLUGIN_BASENAME' ) )
	define( 'FIFO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'FIFO_PLUGIN_NAME' ) )
	define( 'FIFO_PLUGIN_NAME', trim( dirname( FIFO_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'FIFO_PLUGIN_DIR' ) )
	define( 'FIFO_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . FIFO_PLUGIN_NAME );

if ( ! defined( 'FIFO_PLUGIN_URL' ) )
	define( 'FIFO_PLUGIN_URL', WP_PLUGIN_URL . '/' . FIFO_PLUGIN_NAME );

function fifo_plugin_path( $path = '' ) {
	return path_join( FIFO_PLUGIN_DIR, trim( $path, '/' ) );
}

function fifo_plugin_url( $path = '' ) {
	return plugins_url( $path, FIFO_PLUGIN_BASENAME );
}

require_once("language/english.php");
//require_once("language/dutch.php");
//require_once("language/french.php");
//require_once("language/german.php");

function fifo()
{
	global $wpdb;
	echo fifopost_shortcode('');
}

function fifopost_shortcode( $atts ) 
{
	global $wpdb;

	$fifopost_arr = "";

	//[FADEIN_FADEOUT]
	$fifopost_fadeout = get_option('fifopost_fadeout');
	$fifopost_fadein = get_option('fifopost_fadein');
	$fifopost_fade = get_option('fifopost_fade');
	$fifopost_fadestep = get_option('fifopost_fadestep');
	$fifopost_fadewait = get_option('fifopost_fadewait');
	$fifopost_bfadeoutt = get_option('fifopost_bfadeoutt');
	
	$fifopost_noofpost = get_option('fifopost_noofpost');
	$fifopost_categories = get_option('fifopost_categories');
	$fifopost_orderbys = get_option('fifopost_orderbys');
	$fifopost_order = get_option('fifopost_order');
	$fifopost_prefix = get_option('fifopost_prefix');
	
	if(!is_numeric($fifopost_fadeout)){ $fifopost_fadeout = 255; } 
	if(!is_numeric($fifopost_fadein)){ $fifopost_fadein = 0; } 
	if(!is_numeric($fifopost_fade)){ $fifopost_fade = 0; } 
	if(!is_numeric($fifopost_fadestep)){ $fifopost_fadestep = 3; } 
	if(!is_numeric($fifopost_fadewait)){ $fifopost_fadewait = 3000; } 
	if(!is_numeric($fifopost_noofpost)){ $fifopost_noofpost = 10; } 
	
	$sSql = query_posts('cat='.$fifopost_categories.'&orderby='.$fifopost_orderbys.'&order='.$fifopost_order.'&showposts='.$fifopost_noofpost);
	
	if ( ! empty($sSql) ) 
	{
		$count = 0;
		foreach ( $sSql as $sSql ) 
		{
			$title = $sSql->post_title;
			$link = get_permalink($sSql->ID);
			$fifopost_arr = $fifopost_arr . "fifopost_Links[$count] = '$link';fifopost_Titles[$count] = '$title'; ";
			if($count == 0)
			{
				$first_t = $title;
				$first_l = $link;
			}
			$count = $count + 1;
		}
	}
	wp_reset_query();
	
	$fifo = "";
    $fifo = $fifo . "<script type='text/javascript' language='javascript'>function fifopost_SetFadeLinks() { $fifopost_arr;}";

	$fifo = $fifo . 'var fifopost_FadeOut = '.$fifopost_fadeout.';';
	$fifo = $fifo . 'var fifopost_FadeIn = '.$fifopost_fadein.';';
	$fifo = $fifo . 'var fifopost_Fade = '.$fifopost_fade.';';
	$fifo = $fifo . 'var fifopost_FadeStep = '.$fifopost_fadestep.';';
	$fifo = $fifo . 'var fifopost_FadeWait = '.$fifopost_fadewait.';';
	$fifo = $fifo . 'var fifopost_bFadeOutt = '.$fifopost_bfadeoutt.';';

	$fifo = $fifo . '</script>';
    $fifo = $fifo . '<div id="fifopost_css">';
	$fifo = $fifo . $fifopost_prefix .'<a href="'.$first_l.'" id="fifopost_Link">'.$first_t.'</a>';
	$fifo = $fifo . '</div>';
	
	return $fifo;
}


function fifopost_install() 
{
	add_option('fifopost_title', "Fade in fade out post");
	
	add_option('fifopost_fadeout', "225");
	add_option('fifopost_fadein', "0");
	add_option('fifopost_fade', "0");
	add_option('fifopost_fadestep', "3");
	add_option('fifopost_fadewait', "3000");
	add_option('fifopost_bfadeoutt', "true");
	
	add_option('fifopost_noofpost', "10");
	add_option('fifopost_categories', "");
	add_option('fifopost_orderbys', "ID");
	add_option('fifopost_order', "DESC");
	add_option('fifopost_prefix', "Fade in : ");
}

function fifopost_widget($args) 
{
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo get_option('fifopost_title');
	echo $after_title;
	fifo();
	echo $after_widget;
}
	
function fifopost_control() 
{
	echo FIFO_PLUGIN_TITLE;
}

function fifopost_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('fifopost', FIFO_PLUGIN_TITLE, 'fifopost_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('fifopost', array(FIFO_PLUGIN_TITLE, 'widgets'), 'fifopost_control');
	} 
}

function fifopost_deactivation() 
{
	// No action required.
}

function fifopost_option() 
{
	global $wpdb;
	?>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
	<h2><?php echo FIFO_PLUGIN_TITLE; ?></h2>
	<h3><?php echo FIFO_PLUGIN_SUBTITLE; ?></h3>
	<?php
	
	$fifopost_title = get_option('fifopost_title');
	
	//$fifopost_fadeout = get_option('fifopost_fadeout');
	$fifopost_fadein = get_option('fifopost_fadein');
	$fifopost_fade = get_option('fifopost_fade');
	$fifopost_fadestep = get_option('fifopost_fadestep');
	$fifopost_fadewait = get_option('fifopost_fadewait');
	//$fifopost_bfadeoutt = get_option('fifopost_bfadeoutt');
	
	$fifopost_noofpost = get_option('fifopost_noofpost');
	$fifopost_categories = get_option('fifopost_categories');
	$fifopost_orderbys = get_option('fifopost_orderbys');
	$fifopost_order = get_option('fifopost_order');
	$fifopost_prefix = get_option('fifopost_prefix');
	
	if (@$_POST['fifopos_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('fifopost_form_show');
		
		$fifopost_title = stripslashes($_POST['fifopost_title']);
		
		//$fifopost_fadeout = stripslashes($_POST['fifopost_fadeout']);
		$fifopost_fadein = stripslashes($_POST['fifopost_fadein']);
		$fifopost_fade = stripslashes($_POST['fifopost_fade']);
		$fifopost_fadestep = stripslashes($_POST['fifopost_fadestep']);
		$fifopost_fadewait = stripslashes($_POST['fifopost_fadewait']);
		//$fifopost_bfadeoutt = stripslashes($_POST['fifopost_bfadeoutt']);
		
		$fifopost_noofpost = stripslashes($_POST['fifopost_noofpost']);
		$fifopost_categories = stripslashes($_POST['fifopost_categories']);
		$fifopost_orderbys = stripslashes($_POST['fifopost_orderbys']);
		$fifopost_order = stripslashes($_POST['fifopost_order']);
		$fifopost_prefix = stripslashes($_POST['fifopost_prefix']);
		
		update_option('fifopost_title', $fifopost_title );
		
		//update_option('fifopost_fadeout', $fifopost_fadeout );
		update_option('fifopost_fadein', $fifopost_fadein );
		update_option('fifopost_fade', $fifopost_fade );
		update_option('fifopost_fadestep', $fifopost_fadestep );
		update_option('fifopost_fadewait', $fifopost_fadewait );
		//update_option('fifopost_bfadeoutt', $fifopost_bfadeoutt );
		
		update_option('fifopost_noofpost', $fifopost_noofpost );
		update_option('fifopost_categories', $fifopost_categories );
		update_option('fifopost_orderbys', $fifopost_orderbys );
		update_option('fifopost_order', $fifopost_order );
		update_option('fifopost_prefix', $fifopost_prefix );
	}
	
	echo '<form name="fifopost_form" method="post" action="">';
	
	echo '<label for="tag-title">'.FIFO_TITLE.'</label><input  style="width: 250px;" type="text" value="';
	echo $fifopost_title . '" name="fifopost_title" id="fifopost_title" /><p></p>';
	
	//echo '<p>Fadeout:<br><input  style="width: 100px;" type="text" value="';
	//echo $fifopost_fadeout . '" name="fifopost_fadeout" id="fifopost_fadeout" /></p>';
	
	echo '<label for="tag-title">'.FIFO_FADEIN.'</label><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fadein . '" name="fifopost_fadein" id="fifopost_fadein" /><p></p>';
	
	echo '<label for="tag-title">'.FIFO_FADE.'</label><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fade . '" name="fifopost_fade" id="fifopost_fade" /><p></p>';
	
	echo '<label for="tag-title">'.FIFO_FADESTEP.'</label><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fadestep . '" name="fifopost_fadestep" id="fifopost_fadestep" /><p></p>';
	
	echo '<label for="tag-title">'.FIFO_FADEWAIT.'</label><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fadewait . '" name="fifopost_fadewait" id="fifopost_fadewait" /><p></p>';
	
	//echo '<p>fifopost_bfadeoutt:<br><input  style="width: 200px;" type="text" value="';
	//echo $fifopost_bfadeoutt . '" name="fifopost_bfadeoutt" id="fifopost_bfadeoutt" /></p>';
	
	echo '<label for="tag-title">'.FIFO_NUMBER_OF_POST.'</label><input  style="width: 200px;" type="text" value="';
	echo $fifopost_noofpost . '" name="fifopost_noofpost" id="fifopost_noofpost" /><p></p>';
	
	echo '<label for="tag-title">'.FIFO_POST_CATEGORIES.'</label><input  style="width: 200px;" type="text" value="';
	echo $fifopost_categories . '" name="fifopost_categories" id="fifopost_categories" /><p>'.FIFO_POST_CATEGORIES_HELP.'</p>';
	
	echo '<label for="tag-title">'.FIFO_POST_ORDERBY.'</label><input  style="width: 200px;" type="text" value="';
	echo $fifopost_orderbys . '" name="fifopost_orderbys" id="fifopost_orderbys" /><p>'.FIFO_POST_ORDERBY_HELP.'</p>';
	
	echo '<label for="tag-title">'.FIFO_POST_ORDER.'</label><input  style="width: 100px;" type="text" value="';
	echo $fifopost_order . '" name="fifopost_order" id="fifopost_order" /><p>'.FIFO_POST_ORDER_HELP.' </p>';
	
	echo '<label for="tag-title">'.FIFO_POST_PREFIX.'</label><input  style="width: 200px;" type="text" value="';
	echo $fifopost_prefix . '" name="fifopost_prefix" id="fifopost_prefix" /><p></p>';

	echo '<br/><input name="fifopos_submit" id="fifopos_submit" lang="publish" class="button-primary" value="'.FIFO_SUBMIT_BUTTON.'" type="Submit" />';
	wp_nonce_field('fifopost_form_show');
	echo '</form>';
	
	?>
	<h3><?php echo FIFO_PLUGIN_HELP_1; ?></h3>
	<ol>
		<li><?php echo FIFO_PLUGIN_HELP_2; ?></li>
		<li><?php echo FIFO_PLUGIN_HELP_3; ?></li>
		<li><?php echo FIFO_PLUGIN_HELP_4; ?></li>
	</ol>
    <p class="description"><?php echo FIFO_PLUGIN_HELP_5; ?> <a href="http://www.gopiplus.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/" target="_blank">Click here</a></p>
  </div>
</div>
	<?php
}

function fifopost_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page(FIFO_PLUGIN_TITLE, FIFO_PLUGIN_TITLE, 'manage_options', __FILE__, 'fifopost_option' );
	}
}

function fifopost_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'fade-in-fade-out-post-title', fifo_plugin_url('fade-in-fade-out-post-title.js'));
	}
}  

add_shortcode( 'FADEIN_FADEOUT', 'fifopost_shortcode' );
add_action('wp_enqueue_scripts', 'fifopost_add_javascript_files');
add_action('admin_menu', 'fifopost_add_to_menu');
add_action("plugins_loaded", "fifopost_widget_init");
register_activation_hook(__FILE__, 'fifopost_install');
register_deactivation_hook(__FILE__, 'fifopost_deactivation');
add_action('init', 'fifopost_widget_init');
?>