<?php

/**
 * Creates and returns an array of site data from a single site
 */
class AgriLife_Site {

	/**
	 * The current site id
	 * @var int
	 */
	private $site_id;

	/**
	 * The current site theme name
	 * @var string
	 */
	private $site_theme;

	/**
	 * The current site Extension Type (might not be used)
	 * @var string
	 */
	private $ext_type;

	/**
	 * The full array of site data
	 * @var array
	 */
	private $site_info;

	/**
	 * Class constructor
	 * @param int $site_id The requested site id
	 */
	public function __construct( $site_id ) {

		$this->site_id = $site_id;

		$this->set_site_info();

	}

	/**
	 * Setter for $site_info
	 *
	 * @since 0.1
	 */
	private function set_site_info() {

		$site_details = get_blog_details( $this->site_id );

		$site_info = array(
			'site_id' => $site_details->blog_id,
			'site_name' => $site_details->blogname,
			'site_url' => $site_details->siteurl,
		);

		switch_to_blog( $this->site_id );

		$this->set_site_theme();

		$site_info['agency'] = $this->add_site_agency();

		$site_info['ext_type'] = $this->ext_type;

		$this->site_info = $site_info;
		
		restore_current_blog();

	}

	/**
	 * Public getter for $site_info
	 *
	 * @since 0.1
	 * @return array The site info
	 */
	public function get_site_info() {

		return $this->site_info;

	}

	/**
	 * Setter for $site_theme
	 *
	 * @since 0.1
	 */
	private function set_site_theme() {

		$theme = wp_get_theme();

		$this->site_theme = $theme->Name;

	}

	/**
	 * Helper function to add the site agency to the site info array
	 *
	 * @since 0.1
	 * @return array
	 */
	private function add_site_agency() {

		$sitetheme = $this->site_theme;

		if( $sitetheme == 'AgriFlex2' ){

			return $this->agriflex_2_options();

		} else {

			$defaultval = $this->default_options();

			if( !empty( $defaultval ) ){

				return $defaultval;

			} else if( $sitetheme == 'AgriFlex2012' || $sitetheme == 'AgriLife' ){

				return $this->agriflex_2012_options();

			} else {

				return 'Unknown';

			}

		}

	}

	/**
	 * Returns the agency and sets $ext_type for sites not using
	 * Agriflex2, AgriFlex2012, or AgriLife themes
	 *
	 * @since 0.1
	 * @return string Site agenc(y/ies)
	 */
	private function default_options() {

		$agency_top = function_exists('get_field') ? get_field( 'agency_top', 'option' ) : '';

		if( !is_array( $agency_top ) ){

			if( $agency_top == 'extension' ){

				$this->ext_type = get_field( 'ext_type', 'option' );

			}

			return $agency_top;

		} else {

			if( in_array( 'extension', $agency_top ) ){

				$this->ext_type = implode( '/', get_field( 'ext_type', 'option' ) );

			}

			return implode( '/', $agency_top );

		}

	}

	/**
	 * Returns the agency and sets $ext_type for sites that use
	 * AgriFlex 2.x
	 *
	 * @since 0.1
	 * @return string Site agenc(y/ies)
	 */
	private function agriflex_2_options() {

		$agency_payload = $this->agriflex_agency();

		$agencies = array();

		foreach ( $agency_payload['agencies'] as $agency ) {
			$agencies[] = $agency;
		}

		$this->ext_type = $agency_payload['ext-type'];

		return implode( '/', $agencies );

	}

	/**
	 * Returns the agency and sets $ext_type for sites that use
	 * AgriFlex 1.x
	 *
	 * @since 0.1
	 * @return string Site agency(y/ies)
	 */
	private function agriflex_2012_options() {

		$site_options = get_option('AgrilifeOptions');

		$agencies = array();

		if ( $site_options['isResearch'] )
			$agencies[] = 'research';

		if ( $site_options['isExtension'] )
			$agencies[] = 'extension';

		if ( $site_options['isCollege'] )
			$agencies[] = 'college';

		if ( $site_options['isTvmdl'] )
			$agencies[] = 'tvmdl';

		if ( $site_options['isFazd'] )
			$agencies[] = 'fazd';

		$ext_types = array(
			'typical',
			'4h',
			'county',
			'tce',
			'mg',
			'mn',
			'sg',
		);

		$this->ext_type = $ext_types[$site_options['extension_type']];

		return implode( '/', $agencies );

	}

	/**
	 * Helper function to get the agencies from AgriFlex 2.x options
	 *
	 * @since 0.1
	 * @return array The site agency data
	 */
	private function agriflex_agency() {

		$path = get_template_directory();

		$agencies = $this->of_get_option('agency-top');
	  $ext_type = $this->of_get_option( 'ext-type' );
	  $val = array_count_values( $agencies );

	  $active = array();

	  // Add the active agency slugs to the $active array
	  foreach ( $agencies as $k => $v ) {
	    if ( $v == 1 )
	      array_push( $active, $k );
	  }
	  
	  // If there's only one active agency, return true
	  if ( $val[1] == 1 ) {
	    $only = TRUE;
	  } else {
	    $only = FALSE;
	  }

	  // Build the return payload
	  $return = array(
	    'agencies' => $active,
	    'single'   => $only,
	    'ext-type' => $ext_type
	  );

	  return $return;

	}

	/**
	 * Interface to retrieve options from the Options Framework
	 *
	 * @since 0.1
	 * @param  string  $name    The option name
	 * @param  boolean $default Return default
	 * @return array           The options array
	 */
	private function of_get_option( $name = '', $default = false ) {

		$config = get_option( 'optionsframework' );

		if ( ! isset( $config['id'] ) ) {
			return $default;
		}

		$options = get_option( $config['id'] );

		if ( isset( $options[$name] ) ) {
			return $options[$name];
    } else {
      return $options;
    }

	}

}