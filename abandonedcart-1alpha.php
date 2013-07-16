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
			'latestVersion' => '1.1.2'
		) );
		die();
	}

	public function serve_payload() {

		$message = array(
			'ac' => '<a href="http://anattadesign.com/e-commerce-checkout-usability-study/?kme=Clicked%20Link&km_MyAB=Tip%201" style="display:block;">63 checkout user experience guidelines to follow (http://is.gd/n4TXaI)</a>',
			'non-ac' => '<a href="http://anattadesign.com/e-commerce-checkout-usability-study/?kme=Clicked%20Link&km_MyAB=Tip%201" style="display:block;">63 checkout user experience guidelines to follow (http://is.gd/n4TXaI)</a>'
		);

		$message_array = array(
			'ac' => array(
				'<a target="_blank" href="http://anattadesign.com/?kme=Clicked%20Link&km_Widget=tip" style="display:block;">Maintain your site\'s header but deactivate the links. Huge difference.</a>',
				'<a target="_blank" href="http://fishpig.co.uk/blog/magento-forms-prototype-javascript-validation.html" style="display:block;">Customers mess up all the time. Are you helping them when they do?</a>',
				'<a target="_blank" href="http://blog.kissmetrics.com/" style="display:block;">You can remove 3 sets of user inputs today. Take it up a notch!</a>',
				'<a target="_blank" href="http://anattadesign.com/zip-code-at-checkout/?kme=Clicked%20Link&km_Widget=tip" style="display:block;">Use zip codes to prefill city, state, and country. It\'s easy.</a>',
				'<a target="_blank" href="http://anattadesign.com/less-options-people-less-options/?kme=Clicked%20Link&km_Widget=tip" style="display:block;">Don\'t give the customer a choice to register. Just do it for them.</a>',
				'<a target="_blank" href="http://blog.kissmetrics.com/first-step-of-checkout/" style="display:block;">Most cart abandonments happen on Step 1. Time to make a change.</a>',
				'<a target="_blank" href="http://www.magentocommerce.com/wiki/3_-_store_setup_and_management/payment/using_google_checkout_with_magento" style="display:block;">Not everyone uses a credit card online. Have you tried Google Checkout?</a>',
				'<a target="_blank" href="http://www.magentocommerce.com/knowledge-base/entry/setting-up-paypal-for-your-magento-store/" style="display:block;">Not everyone uses a credit card online. Have you tried Paypal?</a>',
				'<a target="_blank" href="http://www.geotrust.com/ssl/ssl-certificates-premium/" style="display:block;">Customers should feel secure. Get a Geotrust Premium SSL certificate.</a>',
				'<a target="_blank" href="http://www.mcafee.com/us/mcafeesecure/index.html" style="display:block;">Customers should feel secure. Try adding a Mcaffee Secure badge.</a>'
			),
			'non-ac' => array(
				'<a target="_blank" href="http://anattadesign.com/?kme=Clicked%20Link&km_Widget=tip" style="display:block;">Maintain your site\'s header but deactivate the links. Huge difference.</a>',
				'<a target="_blank" href="http://fishpig.co.uk/blog/magento-forms-prototype-javascript-validation.html" style="display:block;">Customers mess up all the time. Are you helping them when they do?</a>',
				'<a target="_blank" href="http://blog.kissmetrics.com/" style="display:block;">You can remove 3 sets of user inputs today. Take it up a notch!</a>',
				'<a target="_blank" href="http://anattadesign.com/zip-code-at-checkout/?kme=Clicked%20Link&km_Widget=tip" style="display:block;">Use zip codes to prefill city, state, and country. It\'s easy.</a>',
				'<a target="_blank" href="http://anattadesign.com/less-options-people-less-options/?kme=Clicked%20Link&km_Widget=tip" style="display:block;">Don\'t give the customer a choice to register. Just do it for them.</a>',
				'<a target="_blank" href="http://blog.kissmetrics.com/first-step-of-checkout/" style="display:block;">Most cart abandonments happen on Step 1. Time to make a change.</a>',
				'<a target="_blank" href="http://www.magentocommerce.com/wiki/3_-_store_setup_and_management/payment/using_google_checkout_with_magento" style="display:block;">Not everyone uses a credit card online. Have you tried Google Checkout?</a>',
				'<a target="_blank" href="http://www.magentocommerce.com/knowledge-base/entry/setting-up-paypal-for-your-magento-store/" style="display:block;">Not everyone uses a credit card online. Have you tried Paypal?</a>',
				'<a target="_blank" href="http://www.geotrust.com/ssl/ssl-certificates-premium/" style="display:block;">Customers should feel secure. Get a Geotrust Premium SSL certificate.</a>',
				'<a target="_blank" href="http://www.mcafee.com/us/mcafeesecure/index.html" style="display:block;">Customers should feel secure. Try adding a Mcaffee Secure badge.</a>'
			)
		);

		echo json_encode( array(
			'status' => 'success',
			'data' => $message,
			'data_array' => $message_array
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
