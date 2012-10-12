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

		if ( $api_endpoint_type == 'fetch' && $api_endpoint_call == 'payload' ) {
			// Call the API handler function
			$this->serve( $api_endpoint_type, $api_endpoint_call );
		} else {
			echo json_encode( array(
				'status' => 'error',
				'message' => 'Invalid Key!'
			) );
			die();
		}
	}

	public function serve( $api_endpoint_type, $api_endpoint_call ) {
		if ( $api_endpoint_type == 'fetch' && $api_endpoint_call == 'payload' ) {
			$this->serve_payload();
		} else {
			echo json_encode( array(
				'status' => 'error',
				'latestVersion' => 'Invalid API Endpoint!'
			) );
		}
	}

	public function serve_payload() {

		$payload = array( );

		$payload['overall'][] = array(
			'operator' => array(
				'lt' => 20 // value less than
			),
			'message' => 'Your conversion rate is terrible. Do better!'
		);

		$payload['overall'][] = array(
			'operator' => array(
				'gt' => 30, // value greater than
				'lt' => 50 // value less than
			),
			'message' => 'Your conversion rate is good but not enough. Try harder.'
		);

		$payload['overall'][] = array(
			'operator' => array(
				'gt' => 70 // value greater than
			),
			'message' => 'Your conversion rate is awesome. Let us know.'
		);

		$payload['step'][] = array(
			'step' => 'login',
			'operator' => array(
				'lt' => 70 // value less than
			),
			'message' => 'You are losing people on login step of checkout.'
		);

		$payload['step'][] = array(
			'step' => 'payment',
			'operator' => array(
				'lt' => 50 // value less than
			),
			'message' => "Half of the people on the payment step doesn't go past the payment."
		);

		echo json_encode( array(
			'status' => 'success',
			'payload' => $payload
		) );
		die();
	}

}