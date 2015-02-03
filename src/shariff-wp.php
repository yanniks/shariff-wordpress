<?php
/**
 * Plugin Name: Shariff for Wordpress
 * Plugin URI: http://www.heise.de/newsticker/meldung/c-t-entwickelt-datenschutzfreundliche-Social-Media-Buttons-weiter-2466687.html
 * Description: Shariff enables website users to share their favorite content without compromising their privacy.
 * Version: 1.0.11
 * Author: Heise Zeitschriften Verlag / Yannik Ehlert
 * Author URI: http://www.heise.de
 * Text Domain: shariff
 * Domain Path: /locale/
 * License: MIT
 */
defined('ABSPATH') or die("There is something wrong.");
function init_locale() {
	load_plugin_textdomain('shariff', false, dirname(plugin_basename(__FILE__)).'/locale' );
}
function loadshariff() {
	 echo '<link href="'.plugins_url( 'dep/shariff.complete.css', __FILE__ ).'" rel="stylesheet">'."\n";
}
function shariffsharing($content) {
	global $post;
	$services = "[";
	if (get_option('shariff_gplus',true) == true) {
		$services = $services.'"googleplus"';
		$serv = "g";
	}
	if (get_option('shariff_fb',true) == true) {
		if ($services != "[") {
			$services = $services.",";
			$serv = $serv."f";
		}
		$services = $services.'"facebook"';
	}
	if (get_option('shariff_twitter',true) == true) {if ($services != "[") {
			$services = $services.",";
			$serv = $serv."t";
		}
		$services = $services.'"twitter"';
	}
	if (get_option('shariff_linkedin',false) == true) {
		if ($services != "[") {
			$services = $services.",";
			$serv = $serv."l";
		}
		$services = $services.'"linkedin"';
	}
	if (get_option('shariff_pinterest',false) == true) {
		if ($services != "[") {
			$services = $services.",";
			$serv = $serv."p";
		}
		$services = $services.'"pinterest"';
	}
	if (get_option('shariff_xing',false) == true) {
		if ($services != "[") {
			$services = $services.",";
			$serv = $serv."x";
		}
		$services = $services.'"xing"';
	}
	if (get_option('shariff_reddit',true) == true) {
		$services = $services.',"reddit"';
		$serv = $serv."r";
	}
	if (get_option('shariff_stumbleupon',false) == true) {
		if ($services != "[") {
			$services = $services.",";
			$serv = $serv."s";
		}
		$services = $services.'"stumbleupon"';
	}
	if (get_option('shariff_whatsapp',false) == true) {
		if ($services != "[") {
			$services = $services.",";
		}
		$services = $services.'"whatsapp"';
	}
	if (get_option('shariff_email',false) == true) {
		if ($services != "[") {
			$services = $services.",";
		}
		$services = $services.'"mail"';
	}
	if (get_option('shariff_info',true) == true) {
		if ($services != "[") {
			$services = $services.",";
		}
		$services = $services.'"info"';
	}
	$services = $services."]";
	if (get_option('shariff_beforeafter','before') != 'before') {
		$content2 = $content;
	}
	if (get_option('shariff_image','leer') != 'leer') {
		if (strpos($content,'<img') !== false) {
			$tmp=explode(">",strstr($content,"<img"));
			$imgurls = explode("\"",$tmp[0]);
			$int = 0;
			foreach($imgurls as $imgurl) {
				$int = $int + 1;
				if (strpos($imgurl,"src") !== false) {
					if (!(strpos($imgurls[$int],"http") !== false)) {
						$image = get_site_url()."/";
					}
					$image .= $imgurls[$int];
				}
			}
		} else {
			$image = filter_var(get_option('shariff_image',''), FILTER_SANITIZE_STRING);
		}
	}
	if((!get_option('shariff_hideonoverview',false) || is_single()) && !strpos($content,"shariffhere") !== false) {
		if (!((strpos($content,'hideshariff') !== false) && (strpos($content,'/hideshariff') == false)) && !(get_post_meta($post->ID, 'shariff_enabled', true))) {
			$content2 .= generateshariff($serv,$services,$image);
		}
	}
	if (get_option('shariff_beforeafter','before') != 'after') {
		$content2 .= $content;
	}
	if (strpos($content,"shariffhere")) {
		$content2 = str_replace("shariffhere",generateshariff($serv,$services,$image),$content2);
	}
	if ((strpos($content,'/hideshariff') !== false)) {
		$content2 = str_replace("/hideshariff","hideshariff",$content2);
	} else {
		$content2 = str_replace("hideshariff","",$content2);
	}
	return $content2;
	
}
function generateshariff($serv,$services,$image) {
	if (get_option('shariff_twittervia',"") != "") {
		$twittervia = ' data-twitter-via="'.filter_var(get_option('shariff_twittervia',""),FILTER_SANITIZE_STRING).'"';
	}
	return '<div class="shariff"'.$twittervia.' data-title="'.get_the_title().'" data-info-url="'.get_option('shariff_infourl',"http://ct.de/-2467514").'" data-backend-url="'.plugins_url( 'backend/index.php', __FILE__ ).'" data-temp="'.filter_var(get_option('shariff_temp',"/tmp"),FILTER_SANITIZE_STRING).'" data-ttl="'.filter_var(get_option('shariff_ttl',"60"),FILTER_SANITIZE_STRING).'" data-service="'.$serv.'" data-services=\''.$services.'\' data-image="'.$image.'" data-url="'.get_permalink().'" data-lang="'.__('en', 'shariff').'" data-theme="'.get_option('shariff_color',"colored").'" data-orientation="'.get_option('shariff_orientation',"horizontal").'"></div>';
}
function setting_plat_callback() {
}
function init_settings() {
	add_settings_section('shariff_platforms',__('Shariff platforms',"shariff"),'setting_plat_callback','shariff');
	add_settings_field('shariff_gplus','Google+','setting_gplus_callback','shariff','shariff_platforms');
	add_settings_field('shariff_fb','Facebook','setting_fb_callback','shariff','shariff_platforms');
	add_settings_field('shariff_twitter','Twitter','setting_twitter_callback','shariff','shariff_platforms');
	add_settings_field('shariff_linkedin','LinkedIn','setting_linkedin_callback','shariff','shariff_platforms');
	add_settings_field('shariff_pinterest','Pinterest','setting_pinterest_callback','shariff','shariff_platforms');
	add_settings_field('shariff_xing','XING','setting_xing_callback','shariff','shariff_platforms');
	add_settings_field('shariff_stumbleupon','StumbleUpon','setting_stumbleupon_callback','shariff','shariff_platforms');
	add_settings_field('shariff_reddit','Reddit','setting_reddit_callback','shariff','shariff_platforms');
	add_settings_field('shariff_whatsapp','WhatsApp','setting_whatsapp_callback','shariff','shariff_platforms');
	add_settings_field('shariff_email','E-Mail','setting_email_callback','shariff','shariff_platforms');
	add_settings_section('shariff_other',__('Other Shariff settings',"shariff"),'setting_plat_callback','shariff');
	add_settings_field('shariff_info',__('Privacy information',"shariff"),'setting_info_callback','shariff','shariff_other');
	add_settings_field('shariff_image',__('Default Image URL',"shariff"),'setting_imageurl','shariff','shariff_other');
	add_settings_field('shariff_color',__('Design',"shariff"),'setting_color_callback','shariff','shariff_other');
	add_settings_field('shariff_orientation',__('Orientation',"shariff"),'setting_orientation_callback','shariff','shariff_other');
	add_settings_field('shariff_beforeafter',__('Button location',"shariff"),'setting_before_callback','shariff','shariff_other');
	add_settings_field('shariff_hideonoverview',__('Hide on overview page',"shariff"),'setting_overview_callback','shariff','shariff_other');
	add_settings_field('shariff_ttl','TTL','setting_ttl_callback','shariff','shariff_other');
	add_settings_field('shariff_twittervia','Twitter via Tag','setting_twittervia_callback','shariff','shariff_other');
	add_settings_field('shariff_temp',__('Temp directory',"shariff"),'setting_temp_callback','shariff','shariff_other');
	add_settings_field('shariff_infourl',__('Info URL',"shariff"),'setting_infourl_callback','shariff','shariff_other');
	register_setting('shariff','shariff_gplus');
	register_setting('shariff','shariff_fb');
	register_setting('shariff','shariff_twitter');
	register_setting('shariff','shariff_linkedin');
	register_setting('shariff','shariff_pinterest');
	register_setting('shariff','shariff_xing');
	register_setting('shariff','shariff_reddit');
	register_setting('shariff','shariff_stumbleupon');
	register_setting('shariff','shariff_image');
	register_setting('shariff','shariff_whatsapp');
	register_setting('shariff','shariff_email');
	register_setting('shariff','shariff_info');
	register_setting('shariff','shariff_color');
	register_setting('shariff','shariff_hideonoverview');
	register_setting('shariff','shariff_orientation');
	register_setting('shariff','shariff_beforeafter');
	register_setting('shariff','shariff_ttl');
	register_setting('shariff','shariff_twittervia');
	register_setting('shariff','shariff_temp');
	register_setting('shariff','shariff_infourl');
}
function setting_before_callback() {
	echo '<select name="shariff_beforeafter">
			<option value="before" ';
			if (get_option('shariff_beforeafter') == "before") { echo 'selected'; }
			echo '>'.__("Before","shariff").'</option>
			<option value="after" ';
			if (get_option('shariff_beforeafter') == "after") { echo 'selected'; }
			echo '>'.__("After","shariff").'</option>
		</select> '.__('Show the sharing buttons before or after the article.',"shariff");
}
function checkbox_setting($checkid,$checktitle,$default) {
	echo '<input name="'.$checkid.'" id="'.$checkid.'" type="checkbox" value="1" class="code" ' . checked( 1, get_option($checkid,$default), false) . ' />';
}
function setting_gplus_callback() {
 	checkbox_setting('shariff_gplus','Google+',true);
}
function setting_fb_callback() {
 	checkbox_setting('shariff_fb','Facebook',true);
}
function setting_xing_callback() {
 	checkbox_setting('shariff_xing','XING',false);
}
function setting_stumbleupon_callback() {
 	checkbox_setting('shariff_stumbleupon','XING',false);
}
function setting_twitter_callback() {
 	checkbox_setting('shariff_twitter','Twitter',true);
}
function setting_linkedin_callback() {
 	checkbox_setting('shariff_linkedin','LinkedIn',false);
}
function setting_pinterest_callback() {
 	checkbox_setting('shariff_pinterest','Pinterest',false);
}
function setting_reddit_callback() {
 	checkbox_setting('shariff_reddit','Reddit',false);
}
function setting_overview_callback() {
 	checkbox_setting('shariff_hideonoverview','Hide on overview',false);
}
function setting_imageurl() {
	echo '<input type="text" name="shariff_image" value="'.filter_var(get_option('shariff_image',''), FILTER_SANITIZE_STRING).'"> '.__('Used for services such as Pinterest','shariff');
}
function setting_whatsapp_callback() {
 	checkbox_setting('shariff_whatsapp','WhatsApp',false);
}
function setting_email_callback() {
 	checkbox_setting('shariff_email','E-Mail',false);
}
function setting_info_callback() {
 	checkbox_setting('shariff_info','Privacy information',true);
}
function setting_orientation_callback() {
	echo '<select name="shariff_orientation">
			<option value="horizontal" ';
			if (get_option('shariff_orientation') == "horizontal") { echo 'selected'; }
			echo '>'.__("Horizontal","shariff").'</option>
			<option value="vertical" ';
			if (get_option('shariff_orientation') == "vertical") { echo 'selected'; }
			echo '>'.__("Vertical","shariff").'</option>
		</select>';
}
function setting_ttl_callback() {
	echo '<input type="number" name="shariff_ttl" value="'.filter_var(get_option("shariff_ttl","60"),FILTER_SANITIZE_STRING).'">';
}
function setting_twittervia_callback() {
	echo '<input type="text" name="shariff_twittervia" value="'.filter_var(get_option("shariff_twittervia",""),FILTER_SANITIZE_STRING).'">';
}
function setting_temp_callback() {
	echo '<input type="text" name="shariff_temp" value="'.filter_var(get_option("shariff_temp","/tmp"),FILTER_SANITIZE_STRING).'">';
}
function setting_infourl_callback() {
	echo '<input type="text" name="shariff_infourl" value="'.filter_var(get_option("shariff_infourl","http://ct.de/-2467514"),FILTER_SANITIZE_STRING).'">';
}
function setting_color_callback() {
	echo '<select name="shariff_color">
			<option value="color" ';
			if (get_option('shariff_color') == "color") { echo 'selected'; }
			echo '>'.__("Colored","shariff").'</option>
			<option value="grey" ';
			if (get_option('shariff_color') == "grey") { echo 'selected'; }
			echo '>'.__('Grey',"shariff").'</option>
			<option value="white" ';
			if (get_option('shariff_color') == "white") { echo 'selected'; }
			echo '>'.__("White","shariff").'</option>
			<option value="round" ';
			if (get_option('shariff_color') == "round") { echo 'selected'; }
			echo '>'.__("Round","shariff").'</option>
		</select>';
}
function loadjs() {
	 echo '<script src="'.plugins_url( 'dep/shariff.complete.js', __FILE__ ).'"></script>'."\n";
}
function shariff_options_page() {
	?>
	    <div class="wrap">
	        <h2><?php echo _e("Shariff configuration","shariff")?></h2>
			<?php
				if (version_compare(phpversion(), '5.4.0') >= 0) {
					echo '<br><font size="6" color="green">&#10004;</font> '.__("You're using PHP 5.4 or greater.","shariff").'<br>';
				} else {
					echo '<br><font size="6" color="red">&#x2717;</font> '.__("You're using an outdated PHP version which will cause problems. Please update PHP to 5.4 or greater to have full support.").'<br>';
				}
			?>
	        <form action="options.php" method="POST">
	            <?php settings_fields( 'shariff' ); ?>
	            <?php do_settings_sections( 'shariff' ); ?>
	            <?php submit_button(); ?>
	        </form>
	    </div>
	    <?php
}
function shariffconfigmenu() {
	add_options_page('shariff', 'Shariff', 'manage_options', 'shariff', 'shariff_options_page');
}

function select_init(){
	$options = array("post","page");
	foreach($options as $option) {
		add_meta_box("useshariff", __( 'Shariff settings', 'shariff'), "shariff_box_callback", $option);
	}
}
function shariff_box_callback(){
  global $post;

  echo '<label for="shariff_enabled">'.__("Deactivate Shariff?").'&nbsp; </label>';
  $field_id_value = get_post_meta($post->ID, 'shariff_enabled', true);
  if($field_id_value) $field_id_checked = 'checked';
  echo '<input type="checkbox" name="shariff_enabled" '.$field_id_checked.' />';
}
add_action('save_post', 'save_details');

function save_details(){
	global $post;
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}
	update_post_meta($post->ID, "shariff_enabled", $_POST["shariff_enabled"]);
}
add_action("admin_init", "select_init");
add_action('admin_menu','shariffconfigmenu');
add_action('admin_init','init_settings');
add_action('init','init_locale');
add_action('wp_enqueue_scripts', 'loadshariff');
add_action('wp_footer','loadjs');
add_filter('the_content','shariffsharing');
