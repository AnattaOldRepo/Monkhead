<?php

/**
 * 	Product: WPReadable
 * 	API Version: 1alpha
 */
class wpreadable_api_handler {

	public function __construct() {
		global $monkhead;

		$api_endpoint_type = $monkhead->get_api_endpoint_type();
		$api_endpoint_call = $monkhead->get_api_endpoint_call();

		if ( $api_endpoint_type == 'collect' && $api_endpoint_call == 'ping' ) {
			// Call the API handler function
			$this->serve( $api_endpoint_type, $api_endpoint_call );
		} else {
			echo json_encode( array(
				'status' => 'error',
				'message' => 'Invalid API Endpoint!'
			) );
			die();
		}
	}

	public function serve( $api_endpoint_type, $api_endpoint_call ) {
		if ( $api_endpoint_type == 'collect' && $api_endpoint_call == 'ping' )
				$this->collect_ping();
	}

	public function collect_ping() {
		global $monkhead;

		if ( !isset( $_POST['ping'] ) )
			die();

		$ping_status = $monkhead->collect_ping( 'wpreadable', $_POST['ping'] );

		echo $ping_status ? json_encode( array( 'status' => 'success' ) ) : json_encode( array( 'status' => 'error' ) );
		die();
	}

}
