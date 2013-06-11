<?php

class AgriLife_Data {

	private $sites;

	public function __construct() {

		$this->set_sites();
		$this->set_sites_option();

	}

	private function set_sites() {

		global $wpdb;
		
		$sites = $wpdb->get_results(
			"SELECT blog_id,path FROM {$wpdb->blogs}
			WHERE site_id = '{$wpdb->siteid}'
			AND spam = '0'
			AND deleted ='0'
			AND archived = '0'
			order by blog_id", ARRAY_A
		);

		$this->sites = $sites;

	}

	private function set_sites_option() {

		$sites = $this->sites;

		$site_array = array();

		foreach ( $sites as $site ) {
			$s = new AgriLife_Site( $site['blog_id'] );
			$site_array[] = $s->get_site_info();
		}

		restore_current_blog();

		update_site_option( 'site_data', $site_array );

	}

}