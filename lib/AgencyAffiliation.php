<?php

/**
 * Loads required theme assets
 * @package AgriFlex3
 * @since 1.0
 */
class AgencyAffiliation {

  public function __construct() {

    // Do not provide this menu for AgriFlex2 themes
    if ( wp_get_theme()->Name != 'AgriFlex2' ) {

      // Add admin page
      add_action( 'admin_menu', function(){

        if( !class_exists('acf') ){

          add_action( 'admin_notices', array( $this, 'errormsg_acf' ) );

        } else {

          // Add fields
          require_once( AGDATA_DIR_PATH . '/fields/agency-affiliation.php' );

          try {

            acf_add_options_sub_page( array(
              'page_title'    => 'Agency Affiliation',
              'menu_title'    => 'Agency Affiliation',
              'menu_slug'     => 'agrilifedata-siteaffiliation',
              'capability'    => 'manage_options',
              'parent_slug'   => 'options-general.php'
            ) );

          } catch (Exception $e) {

            add_action( 'admin_notices', array( $this, 'errormsg_acf_add_options_sub_page' ) );

          }

        }

      } );

    }

  }

  public function errormsg_acf() {

    ?>
    <div class="error notice">
      <p>The Agency Affiliation menu is unavailable because Advanced Custom Fields is not installed. Either install the Advanced Custom Fields Pro plugin version 5.0.0 or above, or Advanced Custom Fields plugin and the Options Page Add-on plugin.</p>
    </div>
    <?php

  }

  public function errormsg_acf_add_options_sub_page() {

    ?>
    <div class="error notice">
      <p>The Agency Affiliation menu is unavailable because the function "acf_add_options_sub_page" is not defined. Either install the Advanced Custom Fields Pro plugin version 5.0.0 or above, or Advanced Custom Fields plugin and the Options Page Add-on plugin.</p>
    </div>
    <?php

  }

}
