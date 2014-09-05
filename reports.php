<?php

require '../monkhead-db-credentials.php';

if ( $_GET['key'] != ACCESSKEY ) die();

$connection = mysql_connect( DBHOST, DBUSER, DBPASS );

$result = mysql_select_db( DBNAME ) or die( "database cannot be selected <br>" );

// Fetch Record from Database
$output = "";
$table = "users_ping"; // Enter Your Table Name
$sql = mysql_query( "select * from $table" );
$columns_total = mysql_num_fields( $sql );

// Get The Field Name
for ( $i = 0; $i < $columns_total; $i++ ) {
        $heading = mysql_field_name( $sql, $i );
        $output .= '"' . $heading . '",';
}
$output .="\n";

// Get Records from the table
while ( $row = mysql_fetch_array( $sql ) ) {
        for ( $i = 0; $i < $columns_total; $i++ ) {
                $output .= '"' . $row["$i"] . '",';
        }
        $output .="\n";
}

// Download the file
$filename = "anatta_api_reports_" . date( 'Y-m-d-h:i:s' ) . " .csv";
header( 'Content-type: application/csv' );
header( 'Content-Disposition: attachment; filename=' . $filename );

echo $output;
