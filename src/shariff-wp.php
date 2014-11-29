<?php
/**
 * Plugin Name: Shariff for Wordpress
 * Plugin URI: http://www.heise.de/newsticker/meldung/c-t-entwickelt-datenschutzfreundliche-Social-Media-Buttons-weiter-2466687.html
 * Description: Shariff enables website users to share their favorite content without compromising their privacy.
 * Version: 1.4.2
 * Author: Heise Zeitschriften Verlag / Yannik Ehlert
 * Author URI: http://www.heise.de
 * Text Domain: shariff
 * Domain Path: /locale/
 * License: MIT
 */
defined('ABSPATH') or die("There is something wrong.");

function loadshariff() {
	 echo '<link href="'.plugins_url( 'dep/shariff.min.css', __FILE__ ).'" rel="stylesheet">'."\n";
}
class Shariffwidget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'shariff_widget',
			__( 'Shariff Sharing Widget', 'shariff' ),
			array( 'description' => __( 'Adds the Shariff sharing Widget to the page', 'shairff' ), )
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$services = "[";
		if ($instance['gplus'] == true) {
			$services = $services.'"googleplus"';
			$serv = "g";
		}
		if ($instance['fb'] == true) {
			if ($services != "[") {
				$services = $services.",";
				$serv = $serv."f";
			}
			$services = $services.'"facebook"';
		}
		if ($instance['twitter'] == true) {if ($services != "[") {
				$services = $services.",";
				$serv = $serv."t";
			}
			$services = $services.'"twitter"';
		}
		if ($instance['whatsapp'] == true) {
			if ($services != "[") {
				$services = $services.",";
			}
			$services = $services.'"whatsapp"';
		}
		if ($instance['mail'] == true) {
			if ($services != "[") {
				$services = $services.",";
			}
			$services = $services.'"mail"';
		}
		if ($instance['info'] == true) {
			if ($services != "[") {
				$services = $services.",";
			}
			$services = $services.'"info"';
		}
		$services = $services."]";
		echo '<div id="shariffwidget" class="widget"><div class="shariff" data-backend-url="'.plugins_url( 'backend/index.php', __FILE__ ).'" data-ttl="'.$instance['ttl'].'" data-service="'.$serv.'" data-services=\''.$services.'\' data-url="'.get_permalink().'" data-theme="'.$instance['color'].'" data-orientation="'.$instance['orientation'].'"></div></div>';
	}
	public function form( $instance ) {
		?>
		<p>
		    <input class="checkbox" type="checkbox" <?php checked($instance['gplus'], 'on'); ?> id="<?php echo $this->get_field_id('gplus'); ?>" name="<?php echo $this->get_field_name('gplus'); ?>" />
		    <label for="<?php echo $this->get_field_id('gplus'); ?>">Google+</label><br>
		    <input class="checkbox" type="checkbox" <?php checked($instance['fb'], 'on'); ?> id="<?php echo $this->get_field_id('fb'); ?>" name="<?php echo $this->get_field_name('fb'); ?>" /> 
		    <label for="<?php echo $this->get_field_id('fb'); ?>">Facebook</label><br>
		    <input class="checkbox" type="checkbox" <?php checked($instance['twitter'], 'on'); ?> id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" /> 
		    <label for="<?php echo $this->get_field_id('twitter'); ?>">Twitter</label><br>
		    <input class="checkbox" type="checkbox" <?php checked($instance['whatsapp'], 'on'); ?> id="<?php echo $this->get_field_id('whatsapp'); ?>" name="<?php echo $this->get_field_name('whatsapp'); ?>" /> 
		    <label for="<?php echo $this->get_field_id('whatsapp'); ?>">WhatsApp</label><br>
		    <input class="checkbox" type="checkbox" <?php checked($instance['mail'], 'on'); ?> id="<?php echo $this->get_field_id('mail'); ?>" name="<?php echo $this->get_field_name('mail'); ?>" /> 
		    <label for="<?php echo $this->get_field_id('mail'); ?>">E-Mail</label><br>
		    <input class="checkbox" type="checkbox" <?php checked($instance['info'], 'on'); ?> id="<?php echo $this->get_field_id('info'); ?>" name="<?php echo $this->get_field_name('info'); ?>" /> 
		    <label for="<?php echo $this->get_field_id('info'); ?>">Privacy-Info</label><br><br>
			<label for="<?php echo $this->get_field_id('color'); ?>">Color: </label>
			<select name="<?php echo $this->get_field_name('color'); ?>">
				<option value="color" <?php if ($instance['color'] == "color") { echo 'selected'; } ?>>Colored</option>
				<option value="grey" <?php if ($instance['color'] == "grey") { echo 'selected'; } ?>>Grey</option>
				<option value="white" <?php if ($instance['color'] == "white") { echo 'selected'; } ?>>White</option>
			</select><br>
			<label for="<?php echo $this->get_field_id('orientation'); ?>">Orientation: </label>
			<select name="<?php echo $this->get_field_name('orientation'); ?>">
				<option value="horizontal" <?php if ($instance['orientation'] == "horizontal") { echo 'selected'; } ?>>Horizontal</option>
				<option value="vertical" <?php if ($instance['orientation'] == "vertical") { echo 'selected'; } ?>>Vertical</option>
			</select><br>
		    <label for="<?php echo $this->get_field_id('twitter'); ?>">TTL: </label><input class="text" type="text" id="<?php echo $this->get_field_id('ttl'); ?>" name="<?php echo $this->get_field_name('ttl'); ?>" value="<?php echo $instance["ttl"]; ?>" /> 
		    <br>
		</p>
		<?php
	}
	public function update( $new_instance, $old_instance ) {
    	$instance = $old_instance;
		$instance['twitter'] = $new_instance['twitter'];
	    $instance['gplus'] = $new_instance['gplus'];
		$instance['fb'] = $new_instance['fb'];
		$instance['info'] = $new_instance['info'];
		$instance['orientation'] = $new_instance['orientation'];
		$instance['color'] = $new_instance['color'];
		$instance['mail'] = $new_instance['mail'];
		$instance['whatsapp'] = $new_instance['whatsapp'];
		$instance['ttl'] = $new_instance['ttl'];
		return $instance;
	}
}
function loadjs() {
	 echo '<script src="'.plugins_url( 'dep/shariff.min.js', __FILE__ ).'"></script>'."\n";
}
add_action('wp_enqueue_scripts', 'loadshariff');
add_action('wp_footer','loadjs');
add_action('widgets_init', create_function('', 'return register_widget("Shariffwidget");')
);