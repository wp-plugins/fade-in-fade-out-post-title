<?php

/*
Plugin Name: Fade in fade out post title
Description: Fade in fade out post title, It is an excellent way to transition between two messages.
Author: Gopi.R
Version: 7.0
Plugin URI: http://www.gopipulse.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/
Author URI: http://www.gopipulse.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/
Donate link: http://www.gopipulse.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/
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

function fifo()
{
	global $wpdb;
	
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
	
	$fifopost_arr = "";
	if ( ! empty($sSql) ) 
	{
		$count = 0;
		foreach ( $sSql as $sSql ) 
		{
			@$title = $sSql->post_title;
			@$link = get_permalink($sSql->ID);
			$fifopost_arr = $fifopost_arr . "fifopost_Links[$count] = '$link';fifopost_Titles[$count] = '$title'; ";
			if($count == 0)
			{
				@$first_t = $title;
				@$first_l = $link;
			}
			$count = $count + 1;
		}
	}
	wp_reset_query();
	?>
	<script type="text/javascript" language="javascript">
	function fifopost_SetFadeLinks() 
	{
		<?php echo $fifopost_arr ?>
	}
	var fifopost_FadeOut = <?php echo $fifopost_fadeout; ?>;
	var fifopost_FadeIn = <?php echo $fifopost_fadein; ?>;
	var fifopost_Fade = <?php echo $fifopost_fade; ?>;
	var fifopost_FadeStep = <?php echo $fifopost_fadestep; ?>;
	var fifopost_FadeWait = <?php echo $fifopost_fadewait; ?>;
	var fifopost_bFadeOutt = <?php echo $fifopost_bfadeoutt; ?>;
	</script>
    <div id="gopiplus_css" style="padding:5px;"><?php echo $fifopost_prefix; ?><a href="<?php echo $first_l; ?>" id="fifopost_Link"><?php echo $first_t; ?></a></div>
	<?php
}

add_shortcode( 'FADEIN_FADEOUT', 'fifopost_shortcode' );

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
	
	//$sSql = query_posts('cat='.$fifopost_categories.'&orderby='.$fifopost_orderbys.'&order='.$fifopost_order.'&showposts='.$fifopost_noofpost);
	
	$sSqlMin = "select p.ID, p.post_title, wpr.object_id, ". $wpdb->prefix . "terms.name , ". $wpdb->prefix . "terms.term_id ";
	$sSqlMin = $sSqlMin . "from ". $wpdb->prefix . "terms ";
	$sSqlMin = $sSqlMin . "inner join ". $wpdb->prefix . "term_taxonomy on ". $wpdb->prefix . "terms.term_id = ". $wpdb->prefix . "term_taxonomy.term_id ";
	$sSqlMin = $sSqlMin . "inner join ". $wpdb->prefix . "term_relationships wpr on wpr.term_taxonomy_id = ". $wpdb->prefix . "term_taxonomy.term_taxonomy_id ";
	$sSqlMin = $sSqlMin . "inner join ". $wpdb->prefix . "posts p on p.ID = wpr.object_id ";
	$sSqlMin = $sSqlMin . "where taxonomy= 'category' and p.post_type = 'post' and p.post_status = 'publish'";
	//$sSqlMin = $sSqlMin . "order by object_id; ";
	
	if( ! empty($fifopost_categories) )
	{
		$sSqlMin = $sSqlMin . " and ". $wpdb->prefix . "terms.term_id in($fifopost_categories)";
	}
	
	if( ! empty($fifopost_orderbys) )
	{
		
		if($fifopost_orderbys <> "rand" )
		{
			$sSqlMin = $sSqlMin . " order by p.$fifopost_orderbys";
			
			if( ! empty($fifopost_order) )
			{
				$sSqlMin = $sSqlMin . " $fifopost_order";
			}
		}
		else
		{
			$sSqlMin = $sSqlMin . " order by rand()";
		}
		
	}
	
	if( ! empty($fifopost_noofpost) )
	{
		$sSqlMin = $sSqlMin . " limit 0, $fifopost_noofpost";
	}
	
	//echo $sSqlMin;
	
	$sSql = $wpdb->get_results($sSqlMin);	
	
	if ( ! empty($sSql) ) 
	{
		$count = 0;
		foreach ( $sSql as $sSql ) 
		{
			@$title = $sSql->post_title;
			@$link = get_permalink($sSql->ID);
			$fifopost_arr = $fifopost_arr . "fifopost_Links[$count] = '$link';fifopost_Titles[$count] = '$title'; ";
			if($count == 0)
			{
				@$first_t = $title;
				@$first_l = $link;
			}
			$count = $count + 1;
		}
	}
	
	@$fifo = "";
	//$fifo = $fifo . "<script type='text/javascript' src='".fifo_plugin_url('fade-in-fade-out-post-title.js')."'><script>";
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

}

function fifopost_option() 
{
	global $wpdb;
	echo '<h2>Fade in fade out post title</h2>';
	
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
	
	echo '<p>'.FIFO_TITLE.'<br><input  style="width: 250px;" type="text" value="';
	echo $fifopost_title . '" name="fifopost_title" id="fifopost_title" /></p>';
	
	//echo '<p>Fadeout:<br><input  style="width: 100px;" type="text" value="';
	//echo $fifopost_fadeout . '" name="fifopost_fadeout" id="fifopost_fadeout" /></p>';
	
	echo '<p>'.FIFO_FADEIN.'<br><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fadein . '" name="fifopost_fadein" id="fifopost_fadein" /></p>';
	
	echo '<p>'.FIFO_FADE.'<br><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fade . '" name="fifopost_fade" id="fifopost_fade" /></p>';
	
	echo '<p>'.FIFO_FADESTEP.'<br><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fadestep . '" name="fifopost_fadestep" id="fifopost_fadestep" /></p>';
	
	echo '<p>'.FIFO_FADEWAIT.'<br><input  style="width: 100px;" type="text" value="';
	echo $fifopost_fadewait . '" name="fifopost_fadewait" id="fifopost_fadewait" /></p>';
	
	//echo '<p>fifopost_bfadeoutt:<br><input  style="width: 200px;" type="text" value="';
	//echo $fifopost_bfadeoutt . '" name="fifopost_bfadeoutt" id="fifopost_bfadeoutt" /></p>';
	
	echo '<p>'.FIFO_NUMBER_OF_POST.'<br><input  style="width: 200px;" type="text" value="';
	echo $fifopost_noofpost . '" name="fifopost_noofpost" id="fifopost_noofpost" /></p>';
	
	echo '<p>'.FIFO_POST_CATEGORIES.'<br><input  style="width: 200px;" type="text" value="';
	echo $fifopost_categories . '" name="fifopost_categories" id="fifopost_categories" /> '.FIFO_POST_CATEGORIES_HELP.'</p>';
	
	echo '<p>'.FIFO_POST_ORDERBY.'<br><input  style="width: 200px;" type="text" value="';
	echo $fifopost_orderbys . '" name="fifopost_orderbys" id="fifopost_orderbys" /> '.FIFO_POST_ORDERBY_HELP.'</p>';
	
	echo '<p>'.FIFO_POST_ORDER.'<br><input  style="width: 100px;" type="text" value="';
	echo $fifopost_order . '" name="fifopost_order" id="fifopost_order" /> '.FIFO_POST_ORDER_HELP.' </p>';
	
	echo '<p>'.FIFO_POST_PREFIX.'<br><input  style="width: 200px;" type="text" value="';
	echo $fifopost_prefix . '" name="fifopost_prefix" id="fifopost_prefix" /></p>';

	echo '<input name="fifopos_submit" id="fifopos_submit" lang="publish" class="button-primary" value="'.FIFO_SUBMIT_BUTTON.'" type="Submit" />';
	echo '</form>';
	require_once("help.php");
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

add_action('wp_enqueue_scripts', 'fifopost_add_javascript_files');
add_action('admin_menu', 'fifopost_add_to_menu');
add_action("plugins_loaded", "fifopost_widget_init");
register_activation_hook(__FILE__, 'fifopost_install');
register_deactivation_hook(__FILE__, 'fifopost_deactivation');
add_action('init', 'fifopost_widget_init');
?>