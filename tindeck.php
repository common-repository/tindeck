<?php
/*
Plugin Name: Tindeck
Plugin URI: http://wordpress.org/plugins/easy-modal
Description: Easily add tindeck player to your site with a shortcode.
Author: Wizard Internet Solutions
Version: 1
Author URI: http://wizardinternetsolutions.com
*/
if (!defined('TINDECK'))
    define('TINDECK', 'Tindeck');

if (!defined('TINDECK_SLUG'))
    define('TINDECK_SLUG', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('TINDECK_DIR'))
    define('TINDECK_DIR', WP_PLUGIN_DIR . '/' . TINDECK_SLUG);

if (!defined('TINDECK_URL'))
    define('TINDECK_URL', WP_PLUGIN_URL . '/' . TINDECK_SLUG);

if (!defined('TINDECK_VERSION'))
    define('TINDECK_VERSION', '1' );

class Tindeck {
	protected $messages = array();
	public function __construct()
	{
		add_shortcode( 'tindeck', array(&$this, 'shortcode_tindeck'));
		if (is_admin())
		{
			//add_action('admin_init', array(&$this,'_messages'),10);
			//add_action('admin_menu', array(&$this, '_menus') );
			//add_filter( 'plugin_action_links', array(&$this, '_actionLinks') , 10, 2 );

		}
	}
	public function shortcode_tindeck($atts)
	{
		extract( shortcode_atts( array(
			'id'	=> NULL,
			'height' => 105,
			'width' => 466,
			'credits' => false,
			'fullscreen' => true,
		), $atts ) );
		if($id)
		{?>
			<object width="<?php echo $width?>" height="<?php echo $height?>">
				<param name="movie" value="http://tindeck.com/player/v1/player.swf?trackid=<?php echo $id?>"></param>
				<param name="allowFullScreen" value="<?php echo $fullscreen?>"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="transparent"></param>
				<embed src="http://tindeck.com/player/v1/player.swf?trackid=<?php echo $id?>" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" allowfullscreen="<?php echo $fullscreen?>" width="<?php echo $width?>" height="<?php echo $height?>"></embed>
			</object>
			<?php if($credits == true){?>
			<br/>
			<a href="http://tindeck.com/" target="_blank" title="Upload MP3">Upload MP3</a> and <a href="http://tindeck.com/" target="_blank" title="download mp3">download MP3</a> using <a href="http://tindeck.com/" target="_blank" title="Free MP3 hosting">free MP3 hosting</a> from Tindeck.
			<?php }
		}
	}
	public function _menus()
	{
		add_submenu_page( 'options-general.php', 'Tindeck Settings', 'Tindeck', 'manage_options', TINDECK_SLUG.'-settings', array(&$this, 'settings_page')); 
	}
	public function _actionLinks( $links, $file )
	{
		if ( $file == plugin_basename( __FILE__ ) )
		{
			$posk_links = '<a href="'.get_admin_url().'admin.php?page='.TINDECK_SLUG.'-settings">'.__('Settings').'</a>';
			array_unshift( $links, $posk_links );
		}
		return $links;
	}
	protected $views = array(
		'settings'	=> '/inc/views/settings.php',
	);
	public function load_view($view = NULL)
	{
		if($view) return TINDECK_DIR.$this->views[$view];
	}

	public function settings_page()
	{
		require $this->load_view('settings');
	}
	public function defaultSettings()
	{
		return array();
	}

	public function _messages()
	{
		$this->messages = $this->get_messages();
	}
	public function message($message,$type = 'updated')
	{
		if ( !session_id() )
			session_start();
		$this->messages[] = array(
			'message' => $message,
			'type' => $type
		);
		$_SESSION['easy_modal_messages'][] = array(
			'message' => $message,
			'type' => $type
		);
	}
	public function get_messages($type = NULL)
	{
		if ( !session_id() )
			session_start();
		if (empty($_SESSION['easy_modal_messages']))
			return false;
		$messages = $_SESSION['easy_modal_messages'];
		unset($_SESSION['easy_modal_messages']);
		return $messages;
	}
}
$Tindeck = new Tindeck;