<?php

class AgriLife_Site {

	private $site_id;

	private $site_theme;

	private $ext_type;

	private $site_info;

	public function __construct( $site_id ) {

		$this->site_id = $site_id;

		$this->set_site_info();

	}

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

	}

	public function get_site_info() {

		return $this->site_info;

	}

	private function set_site_theme() {

		$theme = wp_get_theme();

		$this->site_theme = $theme->Name;

	}

	private function add_site_agency() {

		switch ( $this->site_theme ) {
			case 'AgriFlex2' :
				return $this->agriflex_2_options();
				break;
			case 'AgriFlex2012' :
				return $this->agriflex_2012_options();
				break;
			default :
				return 'Unknown';
		}

	}

	private function agriflex_2_options() {

		$agency_payload = agriflex_agency();

		$agencies = array();

		foreach ( $agency_payload['agencies'] as $agency ) {
			$agencies[] = $agency;
		}

		$this->ext_type = $agency_payload['ext-type'];

		return implode( '/', $agencies );

	}

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

}