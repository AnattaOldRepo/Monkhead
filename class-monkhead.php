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

	private $dbhost;
	private $dbname;
	private $dbuser;
	private $dbpass;

	/**
	 *	MySQLi connection handle
	 */

	private $mysqli;

	/**
	 *	Product or Service for which the object is created
	 */

	private $service;

	/**
	 *	API Endpoints
	 */

	private $api_endpoint_type;
	private $api_endpoint_call;

	/**
	 *	Set DB credentials, service whose API is being called for and API endpoint details on instantiation
	 */

	public function __construct( $dbhost, $dbname, $dbuser, $dbpass, $service, $api_endpoint_type, $api_endpoint_call ) {
		// Set Database credentials
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;

		// Set MySQLi connection handle
		$this->mysqli = $this->establish_mysqli_connection();

		// Set Product or Service we are using in this instance
		$this->service = $service;

		// Set API Endpoints
		$this->api_endpoint_type = $api_endpoint_type;
		$this->api_endpoint_call = $api_endpoint_call;
	}

	/**
	 *	Function to return the API endpoint type
	 */

	public function get_api_endpoint_type() {
		return $this->api_endpoint_type;
	}

	/**
	 *	Function to return the API endpoint call
	 */

	public function get_api_endpoint_call() {
		return $this->api_endpoint_call;
	}

	/**
	 *	Function to establish MySQLi connection
	 */

	public function establish_mysqli_connection() {
		$mysqli = mysqli_connect( $this->dbhost, $this->dbuser, $this->dbpass, $this->dbname );
		if ( mysqli_connect_errno( $mysqli ) ) {
			error_log( 'Failed to connect to MySQL: ' . mysqli_connect_error() );
			return false;
		}
		return $mysqli;
	}

}