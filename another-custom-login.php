<?php
/**
 * @package   another-custom-login
 * @author    Nimrod Cohen
 * @license   GPL-2.0+
 * @link      http://wordpress.org/
 * @copyright 2016 Nimrod Cohen
 *
 * @wordpress-plugin
 * Plugin Name:       Another Custom Login
 * Plugin URI:        http://wordpress.org/
 * Description:       This plugin provides an easy way to view change the login and protect your wordpress.
 *                    Another Custom Login Changes login/logout url and protects the wp-login.php page
 * Version:           0.0.01
 * Tag:               0.0.01
 * Author:            Nimrod Cohen
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
 *
 */
class AnotherCustomLogin
{
	private $loginError;

	public function __construct()
	{
		add_action("init",array($this,"protectWPLogin"));
		add_action("init",array($this,"doLoginActions"));
		add_action( 'admin_menu', array($this,"addMenus"));
		add_shortcode("anculo-show-login",array($this,"showLogin"));
		add_action( 'wp_enqueue_scripts', array($this,"enqueueLoginCSS") );
		add_filter( 'body_class',array($this,'addLoginClassToBody'));
		add_filter( 'login_url', array($this,'getLoginUrl'), 10, 3 );
	}

	public static function getSettings()
	{
		$settings = get_option('anculo_settings',false);

		return !$settings ? array(
			"login_page" => false,
			"ralins" => array(),
			"pass_strength" => 0
		) : (array)$settings;
	}

	public static function getSetting($name)
	{
		$settings = self::getSettings();

		return $settings[$name];
	}

	public static function setSettings($args)
	{
		$settings = self::getSettings();

		foreach($args as $key => $val)
			$settings[$key] = $val;

		update_option("anculo_settings",$settings);
	}

	public function isLoginPage()
	{
		$loginUrl = wp_login_url();
		$requestUrl = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		return $loginUrl == $requestUrl;
	}

	private function doLogin()
	{
		$user = wp_signon();

		if(is_wp_error($user))
			$this->loginError = $user->get_error_message();
		else if (isset($_REQUEST['redirect_to']))
		{
			$redirect_to = $_REQUEST['redirect_to'];
			wp_redirect($redirect_to);
		}
		else
			$this->redirectLoggedInUser($user);
	}

	private function redirectLoggedInUser($user)
	{
		$roles = $user->roles;
		$role = array_shift($roles);

		$ralins = self::getSetting("ralins");

		if(isset($ralins[$role]) && strlen($ralins[$role]) > 0)
			wp_redirect(get_page_link($ralins[$role]));
		else if(user_can($user,"manage_options"))
			wp_redirect(get_dashboard_url());
		else
			wp_redirect(home_url());
		exit;
	}

	private function sendResetEmail($email,$key,$login)
	{
		$resetUrl = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($login), 'login');
		$message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $email) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		$message .= "<a href='" . $resetUrl . "'>".$resetUrl."</a>\r\n";

		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$title = sprintf( __('[%s] Password Reset'), $blogname );

		wp_mail( $email, wp_specialchars_decode( $title ), $message );

	}

	private function doLogout()
	{
		wp_logout();
		wp_redirect(wp_login_url());
	}

	private function doResetPassword()
	{
		$this->loginError = false;
		$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['user_login'] );

		if(is_wp_error($user))
		{
			$this->loginError = $user->get_error_message();
			return;
		}

		$pass1 = isset($_POST["pass1"]) ? trim($_POST["pass1"]) : "";
		$pass2 = isset($_POST["pass2"]) ? trim($_POST["pass2"]) : "";

		if(strlen($pass1) == 0)
			$this->loginError = "Password is empty";
		else if($pass1 != $pass2)
			$this->loginError = "Passwords mismatch";

		reset_password($user, $pass1);
		$this->loginError = "Password changed successfully";
	}

	private function doLostPassword()
	{
		$user = false;
		$login = isset($_POST["user_login"]) ? $_POST["user_login"] : false;

		if(!$login)
		{
			$this->loginError = "Invalid login or email address, or address does not exist";
			return false;
		}

		$user = get_user_by("email",$login);

		if(!$user)
			$user = get_user_by("login",$login);

		if (!$user)
		{
			$this->loginError = "Invalid login or email address, or address does not exist";
			return false;
		}

		$key = get_password_reset_key( $user );
		if(is_wp_error($key))
		{
			$this->loginError = "Invalid login or email address, or address does not exist";
			return false;
		}

		$email = $user->user_email;

		$this->sendResetEmail($email, $key,$user->user_login);
		$this->loginError = "Check your email for a link to reset your password";

		return true;
	}

	public function doLoginActions()
	{
		if($this->isLoginPage())
		{
			$this->loginError = false;

			$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

			if (is_user_logged_in() && $action != "logout")
			{
				$this->redirectLoggedInUser(wp_get_current_user());
				return;
			}

			switch ($action)
			{
				case "do_login":
					$this->doLogin();
					break;
				case "do_lostpassword":
					$this->doLostPassword();
					break;
				case "do_resetpassword":
					$this->doResetPassword();
					break;
				case "logout":
					$this->doLogout();
					exit;
			}
		}
	}

	public function protectWPLogin()
	{
		$loginPage = self::getSetting("login_page");

		if(strstr($_SERVER["REQUEST_URI"],"/wp-login.php") > -1 && "" !== $loginPage)
		{
			$link = get_page_link($loginPage).(!empty($_SERVER["QUERY_STRING"]) ? "?".$_SERVER["QUERY_STRING"] : "");
			wp_redirect($link);
			exit;
		}
	}

	public function renderManageScreen()
	{
		include_once("settings.php");
	}

	public function addMenus()
	{
		add_options_page("Another Custom Login","Custom Login","manage_options","anculo-settings",array($this,"renderManageScreen"));
	}

	//this happens after page already started to render.
	public function showLogin($atts)
	{
		$action = isset($_GET["action"])? $_GET["action"] : "login";

		switch($action)
		{
			case "lostpassword":
				return $this->getTemplate("lostpassword",$atts);
			case "rp":
				$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST["login"] );
				if(is_wp_error($user))
				{
					$this->loginError = $user->get_error_message();
					return $this->getTemplate("login", $atts);
				}
				wp_enqueue_script( 'password-strength-meter' );
				wp_enqueue_script('pwd-strength-check',plugin_dir_url(__FILE__)."/scripts/pwd-strength.js",array('password-strength-meter'));
				return $this->getTemplate("resetpassword",$atts);
			case "login":
			default:
				return $this->getTemplate("login",$atts);
		}
	}

	private function getTemplate( $template_name, $attributes = null ) {
		if ( ! $attributes ) {
			$attributes = array();
		}

		ob_start();

		require( 'templates/' . $template_name . '.php');

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function enqueueLoginCSS()
	{
		if($this->isLoginPage())
			wp_enqueue_style('login_css',admin_url("css/login.css"));
	}

	public function addLoginClassToBody($classes)
	{
		if($this->isLoginPage())
			$classes[] = "login";
		return $classes;
	}

	public function getLoginUrl( $origUrl, $redirect = "", $force_reauth = false)
	{
		$login = self::getSetting("login_page");

		if($login && strlen($login) > 0)
			$login = get_page_link($login);
		else
			$login = $origUrl;

		if(isset($redirect) && strlen($redirect) > 0)
			$login = add_query_arg( 'redirect_to', $redirect, $login );
		return $login;
	}
}

$anotherCustomLogin = new AnotherCustomLogin();