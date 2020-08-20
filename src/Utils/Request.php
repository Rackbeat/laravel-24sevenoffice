<?php

namespace KgBot\SO24\Utils;

use App\Classes\ShopifyRestBucket;
use App\Classes\SO24RestBucket;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Config;
use KgBot\SO24\Contracts\BucketContract;
use KgBot\SO24\Exceptions\SO24RequestException;
use SoapClient;
use SoapFault;

libxml_disable_entity_loader( false );

class Request
{

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

	/** @var SoapClient */
	private $soapClient;
	/** @var BucketContract */
	protected $bucket;

	/**
	 * Request constructor.
	 *
	 * @param null           $username
	 * @param null           $password
	 * @param null           $api_token
	 * @param BucketContract $bucket
	 * @param null           $identity
	 * @param array          $options
	 *
	 * @throws SO24RequestException
	 */
	public function __construct( $username, $password, $api_token, BucketContract $bucket, $identity = null, $options = [] ) {
		$this->username = $username ?? Config::get( 'laravel-24so.username' );
		$this->password = $password ?? Config::get( 'laravel-24so.password' );
		$this->api_key  = $api_token ?? Config::get( 'laravel-24so.api_key' );
		$this->identity = $identity;
		$this->options  = $options;
		$this->bucket   = $bucket;

		$this->handleWithExceptions( function () {
			$this->get_auth();
		} );
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

			if ( preg_match( '/https:\/\/api\.24sevenoffice\.com\/authenticate\/V001\/authenticate\.asmx\?wsdl/', $message ) ) {
				try {
					sleep( 2 );
					$this->get_auth();

					return $callback();
				} catch ( \Exception $e ) {

					throw new SO24RequestException( $e->getMessage(), $e->getCode() );
				}
			}

			throw new SO24RequestException( $message, $code );
		}
	}

	public function callsMade() {
		return Config::get( 'laravel-24so.api_limit', 7200 ) - $this->getRemainingLimit( $this->soapClient->__getLastResponseHeaders() );
	}

	public function callLimit() {
		return Config::get( 'laravel-24so.api_limit', 7200 );
	}

	public function callsLeft() {
		return $this->callLimit() - $this->callsMade();
	}

	protected function updateBucket() {
		$this->bucket->setCallsLeft( $this->callsLeft() );
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
	public function call( $action, array $request, $attempts = 0 ) {
		return $this->handleWithExceptions( function () use ( $action, $request, $attempts ) {
			$sleepAttemps = 0;
			while ( $sleepAttemps < 5 && $this->bucket->getCallsLeft() === 0 ) {
				// increment delay if calls left remain 0,
				// technically it could be almost 60 seconds before calls will be > 0 again
				sleep( 2 + $sleepAttemps );
				$sleepAttemps++;
			}

			$service = $this->service();
			$request = $this->parse_query( $request );

			try {
				$response = $service->__soapCall( $action, [ $request ] );
			} catch ( \Exception $exception ) {
				if ( $this->getResponseCode() === 429 && $attempts <= 3 ) {
					sleep( $this->retryAfterValue() );

					return $this->call( $action, $request, $attempts + 1 );
				}

				throw $exception;
			}

			$this->updateBucket();

			return $response;
		} );
	}

	private function retryAfterValue() {
		if ( $this->soapClient->__getLastResponseHeaders() == null ) {
			throw new Exception( 'Cannot be called before an API call.' );
		}

		$headers = $this->soapClient->__getLastResponseHeaders();
		if ( $this->getRemainingLimit( $headers ) <= 1 ) {
			preg_match( '/APILimitReset: \K[\d]+-[\d]+-[\d]+T[\d]+:[\d]+:[\d]+Z/', $headers, $retryAfter );

			return count( $retryAfter ) ? Carbon::parse( $retryAfter[0] )->diffInSeconds( Carbon::now() ) : 3600;
		}

		return 0;
	}

	private function getResponseCode() {
		if ( $this->soapClient->__getLastResponseHeaders() == null ) {
			throw new Exception( 'Cannot be called before an API call.' );
		}
		$headers = $this->soapClient->__getLastResponseHeaders();
		preg_match( "/HTTP\/\d\.\d\s*\K[\d]+/", $headers, $matches );

		return $matches[0];
	}

	private function getRemainingLimit( $headers ) {
		preg_match( '/APILimitRemaining: \K[\d]+/', $headers, $limit );

		return count( $limit ) ? $limit[0] : 7200;
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
	 * @return SoapClient
	 * @throws SoapFault
	 */
	public function service() {
		$options = $this->getOptions();

		$service = new SoapClient( $this->service, $options );
		$service->__setCookie( 'ASP.NET_SessionId', $this->sessionId );

		$this->soapClient = $service;

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
