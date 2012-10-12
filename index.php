<?php

/**
 *	This is the main file which handles all the endpoints of the API
 */

// Load DB credentials: DBHOST, DBNAME, DBUSER, DBPASS
require '../monkhead-db-credentials.php';

// Define absolute path
define( MONKPATH, dirname( __FILE__ ) );

// Define whitelist array for the services and api versions allowed
$whitelist = array(
	// My Abandoned Cart
	'abandonedcart' => array(
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

// Service
if ( isset( $url_parameters[1] ) && strlen( $url_parameters[1] ) != 0 && ctype_alnum( $url_parameters[1] ) )
	$service = $url_parameters[1];
else
	$service = false;

// API Version
if ( isset( $url_parameters[2] ) && strlen( $url_parameters[2] ) != 0 && ctype_alnum( $url_parameters[2] ) )
	$api_version = $url_parameters[2];
else
	$api_version = false;

// API Endpoint type
if ( isset( $url_parameters[3] ) && strlen( $url_parameters[3] ) != 0 && ctype_alnum( $url_parameters[3] ) )
	$api_endpoint_type = $url_parameters[3];
else
	$api_endpoint_type = false;

// API Endpoint call
if ( isset( $url_parameters[4] ) && strlen( $url_parameters[4] ) != 0 && ctype_alnum( $url_parameters[4] ) )
	$api_endpoint_call = $url_parameters[4];
else
	$api_endpoint_call = false;

// Die if the API url is mischievous
if ( ! $service || ! $api_version || ! $api_endpoint_type || ! $api_endpoint_call || ( isset( $url_parameters[5] ) && trim( $url_parameters[5] ) != '' ) ) {
	echo json_encode( array(
		'status' => 'error',
		'message' => 'Bad URL!'
	));
	die();
}

// Die if this API call is not a whitelisted request
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
$monkhead = new Monkhead( DBHOST, DBNAME, DBUSER, DBPASS, $service, $api_endpoint_type, $api_endpoint_call );

// Load the required file based on request
require MONKPATH . '/' . $service . '-' . $api_version . '.php';

// Fire off the class
$service_api_handler = $service . '_api_handler';
$serve = new $service_api_handler();
