<?php

/**
 *	Monkhead Class
 *
 *	Engine which drives the whole thing
 */

class Monkhead {

	/**
	 *	Database credentials
	 */

	private $dbname;
	private $dbuser;
	private $dbpass;
	private $service;
	private $api_endpoint_type;
	private $api_endpoint_call;

	/**
	 *	Set DB credentials, service whose API is being called for and API endpoint details on instantiation
	 */

	public function __construct( $dbname, $dbuser, $dbpass, $service, $api_endpoint_type, $api_endpoint_call ) {
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->service = $service;
		$this->api_endpoint_type = $api_endpoint_type;
		$this->api_endpoint_call = $api_endpoint_call;
	}

}