<?php

/**
 *	Product: AwesomeCheckout
 *	API Version: 1alpha
 */

class awesomecheckout_api_handler {

	public function __construct() {
		global $monkhead;

		$need_to_verify_api_key = true;
		
		$api_endpoint_type = $monkhead->get_api_endpoint_type();
		$api_endpoint_call = $monkhead->get_api_endpoint_call();
		
		if ( $api_endpoint_type == 'status' && $api_endpoint_call == 'latestVersion' )
			$need_to_verify_api_key = false;
		
		// Verify API key
		if ( $need_to_verify_api_key && ! $monkhead->verify_api_key() ) {
			echo json_encode( array(
				'status' => 'error',
				'message' => 'Invalid Key!'
			));
			die();
		}

		// Call the API handler function
		$this->serve( $api_endpoint_type, $api_endpoint_call );
	}

	public function serve( $api_endpoint_type, $api_endpoint_call ) {
		if ( $api_endpoint_type == 'status' ) {
			if ( $api_endpoint_call == 'latestVersion' )
				$this->serve_latest_version();
			else
				$die_flag = true;
		} else {
			$die_flag = true;
		}

		if ( $die_flag ) {
			echo json_encode( array(
				'status' => 'error',
				'latestVersion' => 'Invalid API Endpoint!'
			));
		}
	}

	public function serve_latest_version() {
		echo json_encode( array(
			'status' => 'success',
			'latestVersion' => '1.0'
		));
		die();
	}

}
