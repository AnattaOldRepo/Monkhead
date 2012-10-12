<?php

/**
 * 	Product: AwesomeCheckout
 * 	API Version: 1alpha
 */
class awesomecheckout_api_handler {

	public function __construct() {
		global $monkhead;

		$api_endpoint_type = $monkhead->get_api_endpoint_type();
		$api_endpoint_call = $monkhead->get_api_endpoint_call();

		$allowed_endpoints = array(
			'status' => 'latestVersion',
			'collect' => 'ping'
		);

		$allow_call = false;

		foreach ( $allowed_endpoints as $endpoint_type => $endpoint_call ) {
			if ( $api_endpoint_type == $endpoint_type && $api_endpoint_call == $endpoint_call ) {
				$allow_call = true;
				break;
			}
		}

		if ( $allow_call ) {
			// Call the API handler function
			$this->serve( $api_endpoint_type, $api_endpoint_call );
		} else {
			echo json_encode( array(
				'status' => 'error',
				'latestVersion' => 'Invalid API Endpoint!'
			) );
		}
	}

	public function serve( $api_endpoint_type, $api_endpoint_call ) {
		if ( $api_endpoint_type == 'status' && $api_endpoint_call == 'latestVersion' )
			$this->serve_latest_version();
		else if ( $api_endpoint_type == 'collect' && $api_endpoint_call == 'ping' )
			$this->collect_ping();
	}

	public function serve_latest_version() {
		echo json_encode( array(
			'status' => 'success',
			'latestVersion' => '1.0'
		) );
		die();
	}

	public function collect_ping() {
		global $monkhead;

		if ( !isset( $_POST['ping'] ) )
			die();

		$ping_status = $monkhead->collect_ping( 'awesomecheckout', $_POST['ping'] );

		echo $ping_status ? json_encode( array( 'status' => 'success' ) ) : json_encode( array( 'status' => 'error' ) );
		die();
	}

}
