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

      if( !class_exists('acf') || !function_exists('acf_add_options_sub_page') ){

        add_action( 'admin_notices', array( $this, 'errormsg' ) );

      } else {

        // Add fields
        require_once( AGDATA_DIR_PATH . '/fields/agency-affiliation.php' );

        // Add admin page
        add_action( 'admin_menu', function(){

          acf_add_options_sub_page( array(
            'page_title'    => 'Agency Affiliation',
            'menu_title'    => 'Agency Affiliation',
            'menu_slug'     => 'agrilifedata-siteaffiliation',
            'capability'    => 'manage_options',
            'parent_slug'   => 'options-general.php'
          ) );

        } );

      }

    }

  }

  public function errormsg() {

    if( !class_exists('acf') )
      $msg = 'Advanced Custom Fields is not installed';
    else if( !function_exists('acf_add_options_sub_page') )
      $msg = 'the function "acf_add_options_sub_page" is not defined';

    ?>
    <div class="error notice">
      <p>The Agency Affiliation menu is unavailable because <?php echo $msg; ?>. Either install the Advanced Custom Fields Pro plugin version 5.0.0 or above, or Advanced Custom Fields plugin and the Options Page Add-on plugin.</p>
    </div>
    <?php

  }

}
