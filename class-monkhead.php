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
		$mysqli = new mysqli( $this->dbhost, $this->dbuser, $this->dbpass, $this->dbname );
		if ( $mysqli->connect_errno ) {
			error_log( 'Failed to connect to MySQL: ' . $mysqli->connect_error );
			return false;
		}
		return $mysqli;
	}

	/**
	 *	Function to check if the user already exists in the username
	 */

	public function user_exists( $user_email ) {
		// Do a database query
		$mysqli = $this->mysqli;
		$query = "SELECT id FROM users WHERE email = '$user_email';";
		$result = $mysqli->query( $query );
		$row = $result->fetch_assoc();

		if ( is_null( $row ) )
			return false;
		else
			return (int) $row['id'];
	}

	/**
	 *	Function to create a new user
	 */

	public function create_user( $user_email ) {
		// Write to database
		$mysqli = $this->mysqli;
		$query = "INSERT into users ( email ) VALUES ( '$user_email' );";
		$result = $mysqli->query( $query );

		// If successful, return user id
		if ( $mysqli->insert_id )
			return (int) $mysqli->insert_id;
		else
			return false;
	}

	/**
	 *	Function to create new API key
	 */

	public function create_new_api_key( $user_email, $type = 'single', $label = '' ) {
		// Fetch the user id
		$user_id = $this->user_exists( $user_email );

		// Create a new user if it doesn't exist
		if ( ! $user_id )
			$user_id = $this->create_user( $user_email );

		// Generate API Key
		$api_key = sha1( md5( sha1( $user_email ) . mt_rand( 345, 64634 ) . sha1( md5( $user_email ) ) ) );

		// Write to database
		$mysqli = $this->mysqli;
		$query = "INSERT into api_keys ( user_id, api_key, type, service, label ) VALUES ( '$user_id', '$api_key', '$type', '$this->service', '$label' );";
		$result = $mysqli->query( $query );

		// If successful, return the newly generated api key
		if ( $result )
			return $api_key;
		else
			return false;
	}

}