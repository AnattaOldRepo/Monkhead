<?php

/**
 *	This is the main file which handles all the endpoints of the API
 */

// Load DB credentials: DBNAME, DBUSER, DBPASS
require '../monkhead-db-credentials.php';

// Define absolute path
define( MONKPATH, dirname( __FILE__ ) );

// Define whitelist array for the services and api versions allowed
$whitelist = array(
	// WPReadable
	'wpreadable' => array(
		'version' => array(
			'1',
			'1beta',
			'1alpha'
		)
	),
	// AwesomeCheckout
	'awesomecheckout' => array(
		'version' => array(
			'1',
			'1beta',
			'1alpha'
		)
	)
);

// Lets get the request url and break it into parts for analyzing
$url_parameters = explode( '/', $_SERVER['REQUEST_URI'] );
$service = $url_parameters[1];
$api_version = $url_parameters[2];
$api_endpoint_type = $url_parameters[3];
$api_endpoint_call = $url_parameters[4];

// Die if this is not a whitelisted request
if ( ! array_key_exists( $service, $whitelist ) || ! in_array( $api_version, $whitelist[$service]['version'] ) ) {
	echo json_encode( array(
		'status' => 'error',
		'message' => 'Bad Request!'
	));
	die();
}

// Load monkhead class
require MONKPATH . '/class-monkhead.php';

// Instantiate monkhead class object
$monkhead = new Monkhead( DBNAME, DBUSER, DBPASS, $service, $api_endpoint_type, $api_endpoint_call );

// Load the required file based on request
require MONKPATH . '/' . $service . '-' . $api_version . '.php';
