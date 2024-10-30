<?PHP
/*
Plugin Name: * blog lister
Plugin URI: http://owagu.com
Description: A simple widget showing all blogs in a multi-site network
Author: pete scheepens
Author URI: http://owagu.com
Version: 1.3
*/

add_action('admin_menu', 'go_blli');

function go_blli() {
add_menu_page( 'blog lister', 'blog lister', 'add_users', 'list_me', 'show_blli',plugins_url('/images/icon.png', __FILE__) );
}

function show_blli() {

if (wp_verify_nonce($_REQUEST['blli'],'opts'))
	{
	$blli_opts = get_option('blli_opts');
	$blli_opts['loc'] = $_REQUEST['location']; //
	$blli_opts['who'] = $_REQUEST['who']; //
	update_option('blli_opts',$blli_opts);
	echo "OPTIONS UPDATED";
	$blliset = 1;
	}
	
$blli_opts = get_option('blli_opts');
?>
<div>
<div style="float:left;width:50%;margin:2%;padding:1%;overflow:auto;height:70%;background-color:white;border:2px solid #CCC;border-radius:3px">
<img src='<?PHP echo plugins_url('/images/blli_logo.png', __FILE__); ?>' style='float:left;width:100px;margin: 1px 5px'>
<h2>* Blog Lister - free version - </h2>
Show a hover-box and/or sidebar widget showing off all or some blogs/sites in a WordPress multi-user or network environment.
<div style="clear:both;margin:5px"></div>
	<div style="width:90%;margin:2% auto;padding:2%;overflow:auto;background-color:white;border:2px solid #CCC;border-radius:3px">
	<?PHP if ($blliset) echo "<div style='border:3px solid green;text-align:center;margin:10px auto;width:40%;padding:5px;'>Options saved</div>"; ?>
		
		<form method='POST'>
		<h2>Option Settings</h2>
		Select a location for the site-selector to show:<br/>
		<select name='location'>
		<option value='TL' <?PHP if ($blli_opts['loc'] == 'TL') echo "selected"; ?>  >Top Left of the screen</option>
		<option value='TR' <?PHP if ($blli_opts['loc'] == 'TR') echo "selected"; ?> >Top Right of the screen</option>
		<option value='BL' <?PHP if ($blli_opts['loc'] == 'BL') echo "selected"; ?> >Bottom Left of the screen</option>
		<option value='BR'<?PHP if ($blli_opts['loc'] == 'BR') echo "selected"; ?> >Bottom Right of the screen</option>
		</select>
		<br/><br/>		
		Who can see the site selections ? : <br/>
		<select name='who'>
		<option value='admin' <?PHP if ($blli_opts['who'] == 'admin') echo "selected"; ?>>only administrators</option>
		<option value='registered' <?PHP if ($blli_opts['who'] == 'registered') echo "selected"; ?> >all registered users</option>
		<option value='all' <?PHP if ($blli_opts['who'] == 'all') echo "selected"; ?>>everyone and their brother</option>
		</select>
		<br/><br/>
		maximum amount of sites to show :<br/>
		(leave empty to show all)<br/>
			<input type='number' name='maxcount' value ='<?PHP echo $blli_opts['maxcount']; ?>' ><br/><br/>
			<div style="width:90%;margin:2% auto;padding:2%;overflow:auto;background-color:#CCC;border:1px solid red;border-radius:3px">
			<strong>option(s) below available in our <a href='http://owagu.com/blog-lister-wordpress-network-blog-or-site-switch/' title='download blog lister PREMIUM'>PREMIUM version - download it now</a>.</strong><br /><br />
			Disable the automatic floating menu and use widget instead ?<br/>
			<select name='disable_display'>
			<option value='yes' <?PHP if ($blli_opts['disable_display'] == 'yes') echo "selected"; ?> >yes disable the floating selectionbox. I will use a widget.</option>
			<option value='no' <?PHP if ($blli_opts['disable_display'] == 'no') echo "selected"; ?> >No, I like the floating box a lot !</option>
			</select><br/><br/>
			Select all the sites you want to include in the site selector :<br /><br />
			<?PHP
			// select which sites to allow
			$count = 0;
			
			global $wpdb;
			$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND last_updated != '0000-00-00 00:00:00' ORDER BY last_updated DESC LIMIT 40") , ARRAY_A );

			// echo "<option value='http://" .$details[ 'domain' ] . $details[ 'path' ] . "' title='".get_blog_option( $details[ 'blog_id' ], 'blogname' )."' >" . substr(get_blog_option( $details[ 'blog_id' ], 'blogname' ),0,40)  ."</option>";				

			foreach ($blogs as $details)
			{
			$count++;
			$bname = substr(get_blog_option( $details[ 'blog_id' ], 'blogname' ),0,40);
			echo "<INPUT NAME='selected_sites[]' TYPE='CHECKBOX' VALUE='$bname'";
			if (in_array($bname,$blli_opts['selected_sites'])) 
					{echo "checked ><span style='color:green;font-weight:900'> $count. $bname</span> <br/>";}
				else 
					{ echo "> $count. $bname <br/>"; }
			}
			?>
			</div />
		<br/><br/>
		<?php wp_nonce_field('opts','blli'); ?>

		<input type='submit' value='submit settings' style='cursor:pointer;margin:1px auto;font-size:44px;text-align:center;padding:18px;background-color:lightyellow'>

		</form>	
		
	</div>
	List Blogs was written, and is maintained by : Pete Scheepens - <a href='http://wpprogrammeurs.nl' title='developers home'>wpprogrammeurs.nl</a><br />
	Find more options in our PREMIUM version - <a href='http://owagu.com/blog-lister-wordpress-network-blog-or-site-switch/' title='download blog lister PREMIUM'> download it now</a>
</div>

<div class='fets_left' style="float:left;width:36%;margin:2%;padding:1%;overflow:auto;height:70%;background-color:lightyellow;border:2px solid #CCC;border-radius:3px">
	<img src='<?PHP echo plugins_url('/images/wppr.png', __FILE__); ?>' style='width:90%;margin:1% 4%;'>
	<h2>Latest news from wpprogrammeurs.nl :</h2>
	<?php 	
		if(function_exists('fetch_feed')) 
		{
			include_once(ABSPATH . WPINC . '/feed.php');
			$feed = 'http://wpprogrammeurs.nl/feed/';
			$rss = fetch_feed($feed);
			if (!is_wp_error( $rss ) ) :
				$maxitems = $rss->get_item_quantity(5);
				$rss_items = $rss->get_items(0, $maxitems);
				if ($rss_items):
					echo "<ul>\n";
					foreach ( $rss_items as $item ) :
						echo '<li>';
						echo '<strong><a href="' . $item->get_permalink() . '">' . $item->get_title() . "</a></strong><br />";
						echo $item->get_description() ;
						echo '</li>';
					endforeach;
					echo "</ul>\n";
				endif;
			endif;		
		}
		
	?>
	</div>
	
<div style='clear:both'></div>	

	<div style='text-align:center'>
	This plugin was created by Pete Scheepens @ <a href='http://wpprogrammeurs.nl' title='dutch WordPress Programmers'>wpprogrammeurs.nl</a><br/>
	Need more plugins or want to sell your own ? -> <a href='http://owagu.com' title='your free marketplace for digital content'>owagu.com</a>
	</div> 
	
</div>
<?PHP
}

add_action('wp_footer', 'hover_blli',6);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function hover_blli() {

$blli_opts = get_option('blli_opts');

if ($blli_opts['who'] == 'admin')
	{
	if (! is_admin() ) {return; exit;}
	}
elseif ($blli_opts['who'] == 'registered')
	{
	if (! is_user_logged_in() ) {return; exit;}
	}
$css = "border:2px solid black;
z-index:999999999;
text-align:center;
background-color:white;
padding:10px;
border-radius:15px;
min-width:180px;
-moz-box-shadow:    3px 3px 2px 2px #ccc;
  -webkit-box-shadow: 3px 3px 2px 2px #ccc;
  box-shadow:         3px 3px 2px 2px #ccc;";
if ($blli_opts['loc'] == 'TL') echo "<div style='position:fixed;left:20px;top:165px;$css'>";
elseif ($blli_opts['loc'] == 'TR') echo "<div style='position:fixed;right:200px;top:165px;$css'>";
elseif ($blli_opts['loc'] == 'BL') echo "<div style='position:fixed;left:20px;bottom:100px;$css'>";
elseif ($blli_opts['loc'] == 'BL') echo "<div style='position:fixed;right:200px;bottom:100px;$css'>";
else echo "<div style='position:fixed;right:190px;bottom:50px;$css'>";
?>
<!-- 

START Blog lister plugin for WordPress by wpprogrammeurs.nl - freebie version
premium version can be downloaded from http://owagu.com

-->
<h2>Blog selector</h2>
	<SELECT onChange="window.location=this.options[this.selectedIndex].value;" style='width:98%;overflow:hidden'>
	<?PHP
	echo "<option>select a blog</option>";
	
	global $wpdb;
	$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND last_updated != '0000-00-00 00:00:00' ORDER BY last_updated DESC LIMIT 40") , ARRAY_A );
	if( is_array( $blogs ) ) 
		{
		foreach( $blogs as $details ) 
			{
			$bname = substr(get_blog_option( $details[ 'blog_id' ], 'blogname' ),0,40);

			echo "<option value='http://" .$details[ 'domain' ] . $details[ 'path' ] . "' title='".get_blog_option( $details[ 'blog_id' ], 'blogname' )."' >" . substr(get_blog_option( $details[ 'blog_id' ], 'blogname' ),0,40)  ."</option>";				
			}
		}
	?>
	</select>
<!-- END Blog lister plugin for WordPress by wpprogrammeurs.nl - freebie version -->
<?PHP
echo "</div>";
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Widget
class blli_blogs extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'blog_lister', // Base ID
			'* Blog lister', // Name
			array( 'description' => __( 'Shows all blogs or sites in a network setup through a hot-linked dropdown list !', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$showcount = $instance['showcount'];
		$orderby = $instance['orderby'];
		$maxcats = $instance['maxcats'];
		
		
		$blli_opts = get_option('blli_opts');

		if ($blli_opts['who'] == 'admin')
			{
			if (! is_admin() ) {return; exit;}
			}
		elseif ($blli_opts['who'] == 'registered')
			{
			if (! is_user_logged_in() ) {return; exit;}
			}
		else
			{
			echo $before_widget;
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
			?>
			
			<SELECT onChange="window.location=this.options[this.selectedIndex].value;" style='width:98%;overflow:hidden'>
			<?PHP
			echo "<option>".esc_attr( $maxcats )."</option>"; 
			
			global $wpdb;
			$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND last_updated != '0000-00-00 00:00:00' ORDER BY last_updated DESC LIMIT 40") , ARRAY_A );
			if( is_array( $blogs ) ) 
				{
				foreach( $blogs as $details ) 
					{
					$bname = substr(get_blog_option( $details[ 'blog_id' ], 'blogname' ),0,40);
					echo "<option value='http://" .$details[ 'domain' ] . $details[ 'path' ] . "' title='".get_blog_option( $details[ 'blog_id' ], 'blogname' )."' >" . substr(get_blog_option( $details[ 'blog_id' ], 'blogname' ),0,40)  ."</option>";				
					}
				}
			?>
			</select>
			<br/>
			<?PHP
			
			echo $after_widget;
			}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['showcount'] = strip_tags( $new_instance['showcount'] );
		$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		$instance['maxcats'] = strip_tags( $new_instance['maxcats'] );
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$showcount = $instance[ 'showcount' ];
			$orderby = $instance[ 'orderby' ];
			$maxcats = $instance[ 'maxcats' ];
		}
		else {
			$title = __( 'Other network sites :', 'text_domain' );
		}
		if (empty($maxcats)) $maxcats = 'go to blog : ';
		?>
		<p>This widget shows a hot-linked dropdown list of all the blogs or sites in your network. </p>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
		first line in the dropdown box :<br/> 
		<input class="widefat" name="<?php echo $this->get_field_name( 'maxcats' ); ?>" type="text" value="<?php echo esc_attr( $maxcats ); ?>" />
		</p>
		<?php 
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "blli_blogs" );' ) );
