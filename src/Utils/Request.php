<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 31.3.18.
 * Time: 16.53
 */

namespace KgBot\SO24\Utils;

use KgBot\SO24\Exceptions\SO24RequestException;
use SoapClient;
use SoapFault;

class Request
{
	/**
	 * @var \GuzzleHttp\Client
	 */
	public $client;
	/**
	 * API Key
	 *
	 * @var string $api_key
	 **/
	private $api_key;
	/**
	 * Username
	 *
	 * @var string $username
	 **/
	private $username;
	/**
	 * Password
	 *
	 * @var string $password
	 **/
	private $password;
	/**
	 * Type
	 *
	 * @var string $type
	 **/
	private $type = 'Community';
	/**
	 * Service URL
	 *
	 * @var string $service
	 **/
	private $service;
	/**
	 * Identity ID
	 *
	 * @var string $identity
	 **/
	private $identity = '00000000-0000-0000-0000-000000000000';
	/**
	 * @var array $options
	 */
	private $options = [];

	/**
	 * Request constructor.
	 *
	 * @param null  $token
	 * @param array $options
	 * @param array $headers
	 */
	public function __construct( $username = null, $password = null, $api_token = null, $options = [], $headers = [] ) {
		$this->username = $username ?? config( 'laravel-24so.username' );
		$this->password = $password ?? config( 'laravel-24so.password' );
		$this->api_key  = $api_token ?? config( 'laravel-24so.api_key' );
	}

	/**
	 * @param $callback
	 *
	 * @return mixed
	 * @throws \KgBot\SO24\Exceptions\SO24ClientException
	 * @throws \KgBot\SO24\Exceptions\SO24RequestException
	 */
	public function handleWithExceptions( $callback ) {
		try {
			return $callback();

		} catch ( \Exception $exception ) {

			$message = $exception->getMessage();
			$code    = $exception->getCode();

			throw new SO24RequestException( $message, $code );

		}
	}

	/**
	 * Sets the service.
	 *
	 * @param string $service Which service to use
	 *
	 * @return void
	 **/
	public function set_service( $service = 'Contact/PersonService' ) {
		$this->service = 'http://webservices.24sevenoffice.com/' . $service . '.asmx?WSDL';
	}

	/**
	 * Makes a call to the soap service
	 *
	 * @param string $action  The action to call
	 * @param array  $request The request to make
	 *
	 * @return mixed The result of the call or the exception if errors
	 **/
	public function call( $action, $request ) {
		$this->get_auth();
		try {
			$service = $this->service();
			$request = $this->parse_query( $request );
			$results = $service->__soapCall( $action, [ $request ] );
		} catch ( SoapFault $e ) {
			$results = 'Errors occurred:' . $e;
		}

		return $results;
	}

	/**
	 * Gets and/or sets the authentication
	 *
	 * @return void
	 **/
	private function get_auth() {
		$options                                = [ 'trace' => 1, 'style' => SOAP_RPC, 'use' => SOAP_ENCODED ];
		$params                                 = [];
		$params ["credential"]["Type"]          = $this->type;
		$params ["credential"]["Username"]      = $this->username;
		$encodedPassword                        = md5( mb_convert_encoding( $this->password, 'utf-16le', 'utf-8' ) );
		$params ["credential"]["Password"]      = $encodedPassword;
		$params ["credential"]["ApplicationId"] = $this->api_key;
		$params ["credential"]["IdentityId"]    = $this->identity;
		$authentication                         = new SoapClient( "https://webservices.24sevenoffice.com/authenticate/authenticate.asmx?wsdl", $options );
		$login                                  = true;
		if ( ! empty( $_SESSION['ASP.NET_SessionId'] ) ) {

			$authentication->__setCookie( "ASP.NET_SessionId", $_SESSION['ASP.NET_SessionId'] );
			try {
				$login = ! ( $authentication->HasSession()->HasSessionResult );
			} catch ( SoapFault $fault ) {
				$login = true;
			}
		}
		if ( $login ) {
			$result = ( $temp = $authentication->Login( $params ) );
			// set the session id for next time we call this page
			$_SESSION['ASP.NET_SessionId'] = $result->LoginResult;
			// each seperate webservice need the cookie set
			$authentication->__setCookie( "ASP.NET_SessionId", $_SESSION['ASP.NET_SessionId'] );
			// throw an error if the login is unsuccessful
			if ( $authentication->HasSession()->HasSessionResult == false ) {
				throw new SoapFault( "0", "Invalid credential information." );
			}
		}
	}

	/**
	 * Gets the service
	 *
	 * @return object The current service
	 **/
	private function service() {

		$options = [ 'trace' => 1, 'style' => SOAP_RPC, 'use' => SOAP_ENCODED ];
		$service = new SoapClient ( $this->service, $options );
		$service->__setCookie( "ASP.NET_SessionId", $_SESSION['ASP.NET_SessionId'] );

		return $service;
	}

	/**
	 * Parses the query into a object
	 *
	 * @param array $query The query array
	 *
	 * @return object The query array as an object
	 **/
	private function parse_query( $query ) {
		return json_decode( json_encode( $query ) );
	}
}