<?php

namespace KgBot\SO24\Utils;

use Exception;
use GuzzleHttp\Client;
use KgBot\SO24\Exceptions\SO24RequestException;
use SoapClient;
use SoapFault;

libxml_disable_entity_loader( false );

class Request
{
	/**
	 * @var Client
	 */
	public $client;
	/**
	 * API Key.
	 *
	 * @var string
	 **/
	private $api_key;
	/**
	 * Username.
	 *
	 * @var string
	 **/
	private $username;
	/**
	 * Password.
	 *
	 * @var string
	 **/
	private $password;
	/**
	 * Service URL.
	 *
	 * @var string
	 **/
	public $service;
	/**
	 * Identity ID.
	 *
	 * @var string
	 **/
	private $identity;
	/** @var array */
	protected $options;

	private $sessionId;

	/**
	 * Request constructor.
	 *
	 * @param null  $username
	 * @param null  $password
	 * @param null  $api_token
	 * @param null  $identity
	 * @param array $options
	 */
	public function __construct( $username = null, $password = null, $api_token = null, $identity = null, $options = [] ) {
		$this->username = $username ?? config( 'laravel-24so.username' );
		$this->password = $password ?? config( 'laravel-24so.password' );
		$this->api_key  = $api_token ?? config( 'laravel-24so.api_key' );
		$this->identity = $identity;
		$this->options  = $options;
	}

	/**
	 * @param $callback
	 *
	 * @return mixed
	 * @throws SO24RequestException
	 */
	public function handleWithExceptions( $callback ) {
		try {
			return $callback();
		} catch ( Exception $exception ) {
			$message = $exception->getMessage();
			$code    = $exception->getCode();

			throw new SO24RequestException( $message, $code );
		}
	}

	/**
	 * Makes a call to the soap service.
	 *
	 * @param string       $action  The action to call
	 * @param array|object $request The request to make
	 *
	 * @return mixed The result of the call or the exception if errors
	 * @throws SoapFault
	 */
	public function call( $action, array $request ) {
		$this->get_auth();
		try {
			$service = $this->service();
			$request = $this->parse_query( $request );
			$results = $service->__soapCall( $action, [ $request ] );
		} catch ( SoapFault $e ) {
			$results = 'Errors occurred:' . $e->getMessage();
		}

		return $results;
	}

	/**
	 * Gets and/or sets the authentication.
	 *
	 * @return void
	 * @throws SoapFault
	 */
	private function get_auth(): void {
		$options                           = $this->getOptions();
		$params                            = [];
		$params ['credential']['Username'] = $this->username;
		$encodedPassword                   = $this->password;
		$params ['credential']['Password'] = $encodedPassword;
		if ( $this->identity !== null ) {
			$params ['credential']['IdentityId'] = $this->identity;
		}
		$params ['credential']['ApplicationId'] = $this->api_key;
		$authentication                         = new SoapClient( 'https://api.24sevenoffice.com/authenticate/V001/authenticate.asmx?wsdl', $options );
		$login                                  = true;
		if ( ! empty( $this->sessionId ) ) {
			$authentication->__setCookie( 'ASP.NET_SessionId', $this->sessionId );
			try {
				$login = ! ( $authentication->HasSession()->HasSessionResult );
			} catch ( SoapFault $fault ) {
				$login = true;
			}
		}
		if ( $login ) {
			$result          = $authentication->Login( $params );
			$this->sessionId = $result->LoginResult;
			// each separate webservice need the cookie set
			$authentication->__setCookie( 'ASP.NET_SessionId', $this->sessionId );
		}
	}

	/**
	 * Gets the service.
	 *
	 * @return object The current service
	 * @throws SoapFault
	 */
	public function service() {
		$options = $this->getOptions();

		$service = new SoapClient( $this->service, $options );
		$service->__setCookie( 'ASP.NET_SessionId', $this->sessionId );

		return $service;
	}

	/**
	 * Parses the query into a object.
	 *
	 * @param array $query The query array
	 *
	 * @return object The query array as an object
	 **/
	private function parse_query( $query ) {
		return json_decode( json_encode( $query ) );
	}

	/**
	 * @return array
	 */
	public function getOptions(): array {
		$opts = [
			'ssl'  => [
				'verify_peer'       => array_get( $this->options, 'verify_peer', false ),
				'verify_peer_name'  => array_get( $this->options, 'verify_peer_name', false ),
				'allow_self_signed' => array_get( $this->options, 'allow_self_signed', true )
			],
			'http' => [
				'user_agent' => array_get( $this->options, 'user_agent', config( 'laravel-24so.user_agent' ) )
			]
		];

		return [
			'encoding'           => array_get( $this->options, 'encoding', 'UTF-8' ),
			'verifypeer'         => array_get( $this->options, 'verifypeer', false ),
			'verifyhost'         => array_get( $this->options, 'verifyhost', false ),
			'soap_version'       => array_get( $this->options, 'soap_version', SOAP_1_2 ),
			'trace'              => array_get( $this->options, 'trace', 1 ),
			'exceptions'         => array_get( $this->options, 'exceptions', 1 ),
			'connection_timeout' => array_get( $this->options, 'connection_timeout', 360 ),
			'stream_context'     => array_get( $this->options, 'stream_context', stream_context_create( $opts ) ),
			'cache_wsdl'         => array_get( $this->options, 'cache_wsdl', WSDL_CACHE_NONE ),
			'keep_alive'         => array_get( $this->options, 'keep_alive', false ),
		];
	}
}
