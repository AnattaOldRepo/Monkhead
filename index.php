<?php

/**
 *	This is the main file which handles all the endpoints of the API
 */

define( MONKPATH, dirname( __FILE__ ) );

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

// Die if this is not a whitelisted request
if ( ! array_key_exists( $service, $whitelist ) || ! in_array( $api_version, $whitelist[$service]['version'] ) ) {
	echo json_encode( array(
		'status' => 'error',
		'message' => 'Bad Request!'
	));
	die();
}

// Load the required file based on request
require MONKPATH . '/' . $service . '-' . $api_version . '.php';
