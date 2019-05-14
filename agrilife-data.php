<?php
/**
Plugin Name: AgriLife Data
Plugin URI: https://github.com/AgriLife/AgriLife-Data
Description: Collects and presents data on AgriLife Multisite networks
Version: 0.2
Author: J. Aaron Eaton
Author URI: http://channeleaton.com
Author Email: aaron@channeleaton.com
License:

  Copyright 2013

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define( 'AGDATA_DIR_PATH', plugin_dir_path( __FILE__ ) );
/*
	Array of strings declaring the agency type used by the agrilife-extension-unit plugin.
	Possible values: college, extension, research, tfs, tvmdl
*/
define( 'AGDATA_AGENCY', NULL );
/*
	Array of strings declaring the extension type used by the agrilife-extension-unit plugin.
	Possible values: typical, 4h, county, tce, mg, mn, sg
*/
define( 'AGDATA_EXTTYPE', NULL );

include( AGDATA_DIR_PATH . 'lib/AgencyAffiliation.php' );

new AgencyAffiliation;

/**
 * Use PHPDoc directives if you wish to be able to document the code using a documentation
 * generator.
 *
 * @version	0.1
 */

// Autoload the vendor classes
// @todo Change 'PluginName' to your class name
spl_autoload_register( 'AgrilifeData::vendor_autoload' );

// Autoload the plugin classes
// @todo Change 'PluginName' to your class name
spl_autoload_register( 'AgrilifeData::plugin_autoload' );

class AgrilifeData {

	/*--------------------------------------------*
	 * Attributes
	 *--------------------------------------------*/

	/** Refers to a single instance of this class. */
	private static $instance = null;

	/** The plugin version number */
	private $version = '0.1';

	/** Refers to the slug of the plugin screen. */
	private $plugin_screen_slug = null;

	/** Save the plugin path for easier retrieval */
	private $path = null;

	/** The Settings Framework object */
	private $wpsf = null;

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	PluginName	A single instance of this class.
	 */
	public static function get_instance() {
		return null == self::$instance ? new self : self::$instance;
	} // end get_instance;

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	private function __construct() {

		// Save the plugin path
		$this->path = plugin_dir_path( __FILE__ );

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

    /*
     * Add the options page and menu item.
     * Uncomment the following line to enable the Settings Page for the plugin:
     */
	  add_action( 'network_admin_menu', array( $this, 'plugin_admin_menu' ) );

    /*
		 * Register admin styles and scripts
		 * If the Settings page has been activated using the above hook, the scripts and styles
		 * will only be loaded on the settings page. If not, they will be loaded for all
		 * admin pages.
		 */
		// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site stylesheets and JavaScript
		// add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		// Load the Github Updater for non-WP repository plugins
		// add_action( 'plugins_loaded', array( $this, 'github_updater' ) );

	} // end constructor

	/**
	 * Loads the plugin text domain for translation
	 *
	 * @todo Replace 'plugin-name-locale' with a unique value for your plugin
	 */
	public function plugin_textdomain() {

		$domain = 'agriflex';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

      load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
      load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		/*
		 * Check if the plugin has registered a settings page
		 * and if it has, make sure only to enqueue the scripts on the relevant screens
		 */

	    if ( isset( $this->plugin_screen_slug ) ){

	    	/*
			 * Check if current screen is the admin page for this plugin
			 * Don't enqueue stylesheet or JavaScript if it's not
			 */

			 $screen = get_current_screen();
			 if ( $screen->id == $this->plugin_screen_slug ) {
			 	wp_enqueue_style( 'plugin-name-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
			 } // end if

	    } // end if

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		/*
		 * Check if the plugin has registered a settings page
		 * and if it has, make sure only to enqueue the scripts on the relevant screens
		 */

    if ( isset( $this->plugin_screen_slug ) ){

    	/*
			 * Check if current screen is the admin page for this plugin
			 * Don't enqueue stylesheet or JavaScript if it's not
			 */

			$screen = get_current_screen();
			if ( $screen->id == $this->plugin_screen_slug ) {
				wp_enqueue_script( 'plugin-name-admin-script', plugins_url( 'js/admin.min.js', __FILE__ ), array( 'jquery' ) );
			} // end if

    } // end if

	} // end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {

		wp_enqueue_style( 'agrilife-data-styles', plugins_url( 'css/display.css', __FILE__ ) );

	} // end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
		wp_enqueue_script( 'agrilife-data-scripts', plugins_url( 'js/display.min.js', __FILE__ ), array( 'jquery' ) );
	} // end register_plugin_scripts

	/**
	 * Registers the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @todo Change 'Page Settings' to the title of your plugin admin page
	 * @todo Change 'update_core' to the required capability
	 * @todo Change 'plugin-settings' to the slug of your plugin
	 */
	public function plugin_admin_menu() {

		// require( 'vendor/Settings.php' );

		$this->wpsf = new Settings( $this->path . 'lib/plugin-settings.php' );

		add_submenu_page(
			'sites.php',
			'Site Data',
			'Site Data',
			'manage_network',
			'site-data',
			array( $this, 'plugin_admin_page' )
		);

		add_submenu_page(
			'plugins.php',
			'Export Plugin List',
			'Export Plugin List',
			'manage_network',
			'plugin-list',
			array( $this, 'plugin_list_page' )
		);

	} // end plugin_admin_menu

	/**
	 * Renders the options page for this plugin.
	 */
	public function plugin_admin_page() {

		ob_start();

		$fields = $this->wpsf;
		include_once( 'views/admin.php' );

		$settings_page = ob_get_contents();
		ob_clean();

		echo $settings_page;

	} // end plugin_admin_page

	public function plugin_list_page() {

		ob_start();

		$fields = $this->wpsf;
		include_once( 'views/plugin-list.php' );

		$plugin_list = ob_get_contents();
		ob_clean();

		echo $plugin_list;

	}

	/**
	 * Check the plugin GitHub repository for updates.
	 *
	 * @todo Change the plugin-dir-name to the correct value
	 * @todo Change each instance of 'user' to your Github username
	 * @todo Change each instance of 'repository' to the repository name
	 * @todo Change the 'requires' value if needed
	 * @todo Change the 'tested' value to the WP version you are testing with
	 */
	public function github_updater() {

		/**
		 * Leave the following definition set to false until you are testing the update feature.
		 * Return to false when you are ready to distribute.
		 */
		// if ( ! defined( 'WP_GITHUB_FORCE_UPDATE' ) )
		// 	define( 'WP_GITHUB_FORCE_UPDATE', false );

		if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin

		$config = array(
			'slug'               => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'agrilife-data',
			'api_url'            => 'https://api.github.com/repos/AgriLife/AgriLife-Data',
			'raw_url'            => 'https://raw.github.com/AgriLife/AgriLife-Data/master',
			'github_url'         => 'https://github.com/AgriLife/respoitory',
			'zip_url'            => 'https://github.com/AgriLife/AgriLife-Data/zipball/master',
			'sslverify'          => true,
			'requires'           => '3.0',
			'tested'             => '3.5.1',
			'readme'             => 'README.md',
		);

		$updater = new Updater( $config );

		}

	}

	/**
	 * Autoloads classes in the 'vendor' directory
	 *
	 * @param  string $classname The class name being autoloaded
	 */
	public static function vendor_autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
      DIRECTORY_SEPARATOR .
      'vendor' .
      DIRECTORY_SEPARATOR .
      str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
      '.php';
    if ( file_exists( $filename ) )
      require $filename;

	}

	/**
	 * Autoloads classes in the 'lib' directory
	 *
	 * @param  string $classname The class name being autoloaded
	 */
	public static function plugin_autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
      DIRECTORY_SEPARATOR .
      'lib' .
      DIRECTORY_SEPARATOR .
      str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
      '.php';
    if ( file_exists( $filename ) )
      require $filename;

	}

} // end class

AgrilifeData::get_instance();
