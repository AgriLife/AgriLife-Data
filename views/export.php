<?php

/**
 * This file gets the data from AgriLife_Data and spits out a CSV to be
 * automatically downloaded.
 *
 * We are required to include wp-load.php and AgriLife/Data.php since the form
 * is calling this 'outside' of WordPress.
 */
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
include( '../lib/AgriLife/Data.php' );
$t = new AgriLife_Data;

$sites = get_site_option( 'site_data' );

$fileName = 'site_data_' . date( 'Y-m-d' ) . '.csv';

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Description: File Transfer');
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$fileName}");
header("Expires: 0");
header("Pragma: public");
 
$fh = @fopen( 'php://output', 'w' );

$agencies = array();
$exttypes = array();

foreach( $sites as $data ) {
  $agency = $data['agency'];

  if( count($agency) == 1 && $agency != 'Unknown' ){
    if( !array_key_exists( $agency, $agencies ) ){
      $agencies[$agency] = 1;
    } else {
      $agencies[$agency]++;
    }

    $exttype = $data['ext_type'];
    if( count($exttype) == 1 ){
      if( !array_key_exists( $exttype, $exttypes ) ){
        $exttypes[$exttype] = 1;
      } else {
        $exttypes[$exttype]++;
      }
    }
  }
}

fputcsv( $fh, array('Agency Totals') );

foreach( $agencies as $key=>$value ){
  fputcsv( $fh, array( $key, $value ) );
}

fputcsv( $fh, array('') );
fputcsv( $fh, array('Extension Agency Types') );

foreach( $exttypes as $key=>$value ){
  fputcsv( $fh, array( $key, $value ) );
}

fputcsv( $fh, array('') );
fputcsv( $fh, array_keys( $sites[0] ) );
 
foreach( $sites as $data ) {
  // Put the data into the stream
  fputcsv($fh, $data);
}
// Close the file
fclose($fh);
// Make sure nothing else is sent, our file is done
exit;