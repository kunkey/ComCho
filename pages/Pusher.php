<?php

class Pusher
{
	public static $VERSION = '2.0.0';

	private $settings = array ();

	public function __construct( $auth_key, $secret, $app_id, $debug = false, $host = 'http://api-ap1.pusher.com', $port = '80', $timeout = 30 )
	{
		// Setup defaults
		$this->settings['server'] = $host;
		$this->settings['port']		= $port;
		$this->settings['auth_key'] = $auth_key;
		$this->settings['secret'] = $secret;
		$this->settings['app_id'] = $app_id;
		$this->settings['url']		= '/apps/' . $this->settings['app_id'];
		$this->settings['debug']	= $debug;
		$this->settings['timeout']	= $timeout;

	}
	
	/**
	 * Utility function used to create the curl object with common settings
	 */
	private function create_curl($s_url, $request_method = 'GET', $query_params = array() )
	{
		# Create the signed signature...
		$signed_query = Pusher::build_auth_query_string(
			$this->settings['auth_key'],
			$this->settings['secret'],
			$request_method,
			$s_url,
			$query_params);

		$full_url = $this->settings['server'] . ':' . $this->settings['port'] . $s_url . '?' . $signed_query;
		
		# Set cURL opts and execute request
		$ch = curl_init();
		if ( $ch === false )
		{
			throw new PusherException('Could not initialise cURL!');
		}
		
		curl_setopt( $ch, CURLOPT_URL, $full_url );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array ( "Content-Type: application/json" ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $this->settings['timeout'] );
		
		return $ch;
	}

	public static function build_auth_query_string($auth_key, $auth_secret, $request_method, $request_path,
		$query_params = array(), $auth_version = '1.0', $auth_timestamp = null)
	{ 
		$params = array();
		$params['auth_key'] = $auth_key;
		$params['auth_timestamp'] = (is_null($auth_timestamp)?time() : $auth_timestamp);
		$params['auth_version'] = $auth_version;
		
		$params = array_merge($params, $query_params);
		ksort($params);
		
		$string_to_sign = "$request_method\n" . $request_path . "\n" . Pusher::array_implode( '=', '&', $params );

		$auth_signature = hash_hmac( 'sha256', $string_to_sign, $auth_secret, false );
		
		$params['auth_signature'] = $auth_signature;
		ksort($params);
		
		$auth_query_string = Pusher::array_implode( '=', '&', $params );
		
		return $auth_query_string;
	}

	public static function array_implode( $glue, $separator, $array ) {
			if ( ! is_array( $array ) ) return $array;
			$string = array();
			foreach ( $array as $key => $val ) {
					if ( is_array( $val ) )
							$val = implode( ',', $val );
					$string[] = "{$key}{$glue}{$val}";

			}		 
			return implode( $separator, $string );
	}


	public function trigger( $channel, $event, $payload, $socket_id = null, $debug = false, $already_encoded = false )
	{
		$query_params = array();
		
		if ( $socket_id !== null )
		{
			$query_params['socket_id'] = $socket_id;
		}
		
		$s_url = $this->settings['url'] . '/channels/' . $channel . '/events';		
		
		$payload_encoded = $already_encoded ? $payload : json_encode( $payload );
		$query_params['body_md5'] = md5( $payload_encoded );
		
		$query_params['name'] = $event;

		$ch = $this->create_curl( $s_url, 'POST', $query_params );

		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload_encoded );

		$response = curl_exec( $ch );

		curl_close( $ch );

		if ( $response == "202 ACCEPTED\n" && $debug == false )
		{
			return true;
		}
		elseif ( $debug == true || $this->settings['debug'] == true )
		{
			return $response;
		}
		else
		{
			return false;
		}

	}
	
	public function get_channel_info($channel, $options = array() )
	{
		$s_url = $this->settings['url'] . '/channels/' . $channel . '/stats'; 

		$ch = $this->create_curl( $s_url, 'GET', $options );

		$response = curl_exec( $ch );

		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if($http_status == 200)
		{
			$response = json_decode($response);
		}
		else
		{
			$response = false;
		}

		curl_close( $ch );
		
		return $response;
	}
	
	public function get_channels($options = array())
	{
		$s_url = $this->settings['url'] . '/channels';	

		$ch = $this->create_curl( $s_url, 'GET', $options );

		$response = curl_exec( $ch );
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if($http_status == 200)
		{
			$response = json_decode($response);
			$response->channels = get_object_vars( $response->channels );
		}
		else
		{
			$response = false;
		}

		curl_close( $ch );
		
		return $response;
	}

	public function socket_auth( $channel, $socket_id, $custom_data = false )
	{

		if($custom_data == true)
		{
			$signature = hash_hmac( 'sha256', $socket_id . ':' . $channel . ':' . $custom_data, $this->settings['secret'], false );
		}
		else
		{
			$signature = hash_hmac( 'sha256', $socket_id . ':' . $channel, $this->settings['secret'], false );
		}

		$signature = array ( 'auth' => $this->settings['auth_key'] . ':' . $signature );
		// add the custom data if it has been supplied
		if($custom_data){
			$signature['channel_data'] = $custom_data;
		}
		return json_encode( $signature );

	}


	public function presence_auth( $channel, $socket_id, $user_id, $user_info = false )
	{

		$user_data = array( 'user_id' => $user_id );
		if($user_info == true)
		{
			$user_data['user_info'] = $user_info;
		}

		return $this->socket_auth($channel, $socket_id, json_encode($user_data) );
	}

}

