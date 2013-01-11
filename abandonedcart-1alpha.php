<?php

/**
 * 	Product: AbandonedCart
 * 	API Version: 1alpha
 */
class abandonedcart_api_handler {

	public function __construct() {
		global $monkhead;

		$api_endpoint_type = $monkhead->get_api_endpoint_type();
		$api_endpoint_call = $monkhead->get_api_endpoint_call();

		$allowed_endpoints = array(
			'status' => 'latestVersion',
			'fetch' => 'payload',
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
				'message' => 'Invalid API Endpoint!'
			) );
			die();
		}
	}

	public function serve( $api_endpoint_type, $api_endpoint_call ) {
		if ( $api_endpoint_type == 'status' && $api_endpoint_call == 'latestVersion' ) {
			$this->serve_latest_version();
		} else if ( $api_endpoint_type == 'fetch' && $api_endpoint_call == 'payload' ) {
			$this->serve_payload();
		} else if ( $api_endpoint_type == 'collect' && $api_endpoint_call == 'ping' ) {
			$this->collect_ping();
		}
	}

	public function serve_latest_version() {
		echo json_encode( array(
			'status' => 'success',
			'latestVersion' => '1.0.1'
		) );
		die();
	}

	public function serve_payload() {

		$message = array(
			'ac' => 'Read why it is important to care about every detail on checkout - <a href="#">Blog post</a>',
			'non-ac' => 'Adding security badges help improve conversion rates! Read more - <a href="#">Blog post</a>'
		);

		echo json_encode( array(
			'status' => 'success',
			'data' => $message
		) );
		die();
	}

	public function collect_ping() {
		global $monkhead;

		if ( !isset( $_POST['ping'] ) )
			die();

		$ping_status = $monkhead->collect_ping( 'abandonedcart', $_POST['ping'] );

		echo $ping_status ? json_encode( array( 'status' => 'success' ) ) : json_encode( array( 'status' => 'error' ) );
		die();
	}

}