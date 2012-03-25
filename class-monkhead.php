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
	 *	Function to verify that an email is valid.
	 *
	 *	Credit: WordPress
	 */

	public function is_email( $email ) {

		// Test for the minimum length the email can be
		if ( strlen( $email ) < 3 )
			return false;

		// Test for an @ character after the first position
		if ( strpos( $email, '@', 1 ) === false )
			return false;

		// Split out the local and domain parts
		list( $local, $domain ) = explode( '@', $email, 2 );

		// LOCAL PART
		// Test for invalid characters
		if ( !preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) )
			return false;

		// DOMAIN PART
		// Test for sequences of periods
		if ( preg_match( '/\.{2,}/', $domain ) )
			return false;

		// Test for leading and trailing periods and whitespace
		if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain )
			return false;

		// Split the domain into subs
		$subs = explode( '.', $domain );

		// Assume the domain will have at least two subs
		if ( 2 > count( $subs ) )
			return false;

		// Loop through each sub
		foreach ( $subs as $sub ) {
			// Test for leading and trailing hyphens and whitespace
			if ( trim( $sub, " \t\n\r\0\x0B-" ) !== $sub )
				return false;

			// Test for invalid characters
			if ( !preg_match('/^[a-z0-9-]+$/i', $sub ) )
				return false;
		}

		// Congratulations your email made it!
		return true;
	}

	/**
	 *	Function to check if the user already exists
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
	 *	Function to get user details
	 *
	 *	@param Integer (User ID) or String (User's email)
	 */

	public function get_user( $user ) {

		if ( is_int( $user ) ) // If a user ID was passed
			$query = 'SELECT * FROM users WHERE id = ' . (int) $user . ';';
		else if ( $this->is_email( $user ) ) // If user's email was passed
			$query = "SELECT * FROM users WHERE email = '$user';";
		else // something else which is not an identifier
			return false;

		// Make a database query
		$mysqli = $this->mysqli;
		$result = $mysqli->query( $query );
		$row = $result->fetch_assoc();

		return $row;
	}

	/**
	 *	Function to get user ID from email
	 */

	public function get_user_id_from_email( $email ) {
		if ( ! $this->is_email( $email ) )
			return false;

		// Make a database query
		$query = "SELECT id FROM users WHERE email = '$email';";
		$mysqli = $this->mysqli;
		$result = $mysqli->query( $query );
		$row = $result->fetch_assoc();

		return (int) $row['id'];
	}

	/**
	 *	Function to update user details
	 *
	 *	Right now updates email address, as thats the only user detail we are saving as of now
	 *
	 *	@param User ID and array of details that we want to modify
	 */

	public function update_user( $user_id, $user_details ) {
		// Fetch all the existing details and then merge it with the modifications
		$user_existing_details = $this->get_user( $user_id );
		$user_details = array_merge( $user_existing_details, $user_details );

		// Write to database
		$mysqli = $this->mysqli;
		$query = "UPDATE users SET email = '{$user_details['email']}' WHERE id = $user_id;";
		$result = $mysqli->query( $query );

		if ( $result )
			return true;
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

	/**
	 *	Function to verify the api key for current API call
	 */

	public function verify_api_key() {
		// Get the API key sent
		$api_key = $_REQUEST['api_key'];

		// Fetch the service name for this API key
		$mysqli = $this->mysqli;
		$query = "SELECT service FROM api_keys WHERE api_key = '$api_key';";
		$result = $mysqli->query( $query );
		$row = $result->fetch_assoc();

		// Check the service which this API key belongs to with the service for which the current API call was made for
		if ( $this->service == $row['service'] )
			return true;
		else
			return false;
	}

	/**
	 *	Function to delete API key (marking it as inactive)
	 */

	public function delete_api_key( $api_key ) {
		// Update its status to be "inactive"
		$mysqli = $this->mysqli;
		$query = "UPDATE api_keys SET status = 'inactive' WHERE api_key = '$api_key';";
		$result = $mysqli->query( $query );

		if ( $result )
			return true;
		else
			return false;
	}
	
}