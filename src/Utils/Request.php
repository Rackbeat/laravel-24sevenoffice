<?php

namespace KgBot\SO24\Utils;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use KgBot\SO24\Classmaps\AccountService\AccountData;
use KgBot\SO24\Classmaps\AccountService\BundleList;
use KgBot\SO24\Classmaps\AccountService\GetAccountListResponse;
use KgBot\SO24\Classmaps\AccountService\GetTypeListResponse;
use KgBot\SO24\Classmaps\AccountService\SaveBundleListResponse;
use KgBot\SO24\Classmaps\AccountService\TypeData;
use KgBot\SO24\Classmaps\Addresses\Address;
use KgBot\SO24\Classmaps\Addresses\Addresses;
use KgBot\SO24\Classmaps\AuthenticateService\Client;
use KgBot\SO24\Classmaps\AuthenticateService\GetIdentitiesResponse;
use KgBot\SO24\Classmaps\AuthenticateService\Identity;
use KgBot\SO24\Classmaps\AuthenticateService\User;
use KgBot\SO24\Classmaps\ClientService\AccountingGroup;
use KgBot\SO24\Classmaps\ClientService\Currency;
use KgBot\SO24\Classmaps\ClientService\EmailAddress;
use KgBot\SO24\Classmaps\ClientService\FaxNumber;
use KgBot\SO24\Classmaps\ClientService\GetClientInformationResponse;
use KgBot\SO24\Classmaps\ClientService\GetCurrencyListResponse;
use KgBot\SO24\Classmaps\ClientService\GetTypeGroupListResponse;
use KgBot\SO24\Classmaps\ClientService\PhoneNumber;
use KgBot\SO24\Classmaps\CompanyService\Company;
use KgBot\SO24\Classmaps\CompanyService\CompanyMap;
use KgBot\SO24\Classmaps\CompanyService\GetCompaniesResponse;
use KgBot\SO24\Classmaps\CompanyService\Relation;
use KgBot\SO24\Classmaps\CompanyService\SaveCompanyResponse;
use KgBot\SO24\Classmaps\CompanyService\SaveRelationsResponse;
use KgBot\SO24\Classmaps\Dimension;
use KgBot\SO24\Classmaps\InvoiceService\ChangeState;
use KgBot\SO24\Classmaps\InvoiceService\DeliveryMethod;
use KgBot\SO24\Classmaps\InvoiceService\GetInvoicesResponse;
use KgBot\SO24\Classmaps\InvoiceService\GetInvoicesResult;
use KgBot\SO24\Classmaps\InvoiceService\InvoiceOrder;
use KgBot\SO24\Classmaps\InvoiceService\InvoiceRow;
use KgBot\SO24\Classmaps\InvoiceService\RowType;
use KgBot\SO24\Classmaps\PersonService\GetPersonsResponse;
use KgBot\SO24\Classmaps\PersonService\PersonItem;
use KgBot\SO24\Classmaps\PersonService\RelationData;
use KgBot\SO24\Classmaps\ProductService\Categories\Category;
use KgBot\SO24\Classmaps\ProductService\Categories\GetCategoriesResponse;
use KgBot\SO24\Classmaps\ProductService\Categories\SaveCategoriesResponse;
use KgBot\SO24\Classmaps\ProductService\GetProductsResponse;
use KgBot\SO24\Classmaps\ProductService\GetProductsResult;
use KgBot\SO24\Classmaps\ProductService\Product;
use KgBot\SO24\Classmaps\ProductService\SaveProductsResponse;
use KgBot\SO24\Classmaps\TimeService\GetHoursResponse;
use KgBot\SO24\Classmaps\TimeService\GetHoursResult;
use KgBot\SO24\Classmaps\TimeService\Hour;
use KgBot\SO24\Classmaps\TransactionService\GetTransactionsResponse;
use KgBot\SO24\Classmaps\TransactionService\Transaction;
use KgBot\SO24\Classmaps\UserDefinedDimensions;
use KgBot\SO24\Contracts\BucketContract;
use KgBot\SO24\Contracts\SessionContract;
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

	/** @var SoapClient */
	private $soapClient;
	/** @var BucketContract */
	protected $bucket;
	/** @var SessionContract */
	protected $session;

	/**
	 * Request constructor.
	 *
	 * @param null            $username
	 * @param null            $password
	 * @param null            $api_token
	 * @param BucketContract  $bucket
	 * @param SessionContract $session
	 * @param null            $identity
	 * @param array           $options
	 *
	 * @throws SO24RequestException
	 */
	public function __construct( $username, $password, $api_token, BucketContract $bucket, SessionContract $session, $identity = null, $options = [] ) {
		$this->username = $username ?? Config::get( 'laravel-24so.username' );
		$this->password = $password ?? Config::get( 'laravel-24so.password' );
		$this->api_key  = $api_token ?? Config::get( 'laravel-24so.api_key' );
		$this->identity = $identity;
		$this->options  = $options;
		$this->bucket   = $bucket;
		$this->session  = $session;

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
	 * @param int          $attempts
	 *
	 * @return mixed The result of the call or the exception if errors
	 * @throws SO24RequestException
	 */
	public function call( $action, array $request, $attempts = 0 ) {
		return $this->handleWithExceptions( function () use ( $action, $request, $attempts ) {
			$sleepAttempts = 0;
			while ( $sleepAttempts < 5 && $this->bucket->getCallsLeft() === 0 ) {
				// increment delay if calls left remain 0,
				// technically it could be almost 60 seconds before calls will be > 0 again
				sleep( 2 + $sleepAttempts );
				$sleepAttempts++;
			}

			$service = $this->service();
			$request = $this->parse_query( $request );

			try {
				$response = $service->__soapCall( $action, [ $request ] );
			} catch ( \Exception $exception ) {
				/*if ( $attempts <= 3 && $this->getResponseCode() === 429 ) {
					sleep( 1 ); // todo check if this will work because we are allowed approx 25 req. per second and 429 error is not our API limit but standard too much requests made (throttling BUT NOT API LIMIT)

					return $this->call( $action, $request, $attempts + 1 );
				}*/

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
		if ( ! empty( $sessionId = $this->session->getSessionId() ) ) {
			$authentication->__setCookie( 'ASP.NET_SessionId', $sessionId );
			try {
				$login = ! ( $authentication->HasSession()->HasSessionResult );
			} catch ( SoapFault $fault ) {
				$login = true;
			}
		}
		if ( $login ) {
			$result = $authentication->Login( $params );
			$this->session->setSessionId( $result->LoginResult );
			// each separate webservice need the cookie set
			$authentication->__setCookie( 'ASP.NET_SessionId', $result->LoginResult );
		}
	}

	/**
	 * @return SoapClient
	 * @throws SoapFault
	 */
	public function service() {
		$options = $this->getOptions();

		$service = new SoapClient( $this->service, $options );
		$service->__setCookie( 'ASP.NET_SessionId', $this->session->getSessionId() );

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
				'verify_peer'       => Arr::get( $this->options, 'verify_peer', false ),
				'verify_peer_name'  => Arr::get( $this->options, 'verify_peer_name', false ),
				'allow_self_signed' => Arr::get( $this->options, 'allow_self_signed', true )
			],
			'http' => [
				'user_agent' => Arr::get( $this->options, 'user_agent', config( 'laravel-24so.user_agent' ) )
			]
		];

		return [
			'encoding'           => Arr::get( $this->options, 'encoding', 'UTF-8' ),
			'verifypeer'         => Arr::get( $this->options, 'verifypeer', false ),
			'verifyhost'         => Arr::get( $this->options, 'verifyhost', false ),
			'soap_version'       => Arr::get( $this->options, 'soap_version', SOAP_1_2 ),
			'trace'              => Arr::get( $this->options, 'trace', 1 ),
			'exceptions'         => Arr::get( $this->options, 'exceptions', 1 ),
			'connection_timeout' => Arr::get( $this->options, 'connection_timeout', 500 ),
			'stream_context'     => Arr::get( $this->options, 'stream_context', stream_context_create( $opts ) ),
			'cache_wsdl'         => Arr::get( $this->options, 'cache_wsdl', WSDL_CACHE_NONE ),
			'classmap'           => [
				// Time Service
				'GetHoursResponse'             => GetHoursResponse::class,
				'GetHoursResult'               => GetHoursResult::class,
				'Hour'                         => Hour::class,
				// Products
				'GetProductsResponse'          => GetProductsResponse::class,
				'GetProductsResult'            => GetProductsResult::class,
				'Product'                      => Product::class,
				'SaveProductsResponse'         => SaveProductsResponse::class,
				// Categories
				'GetCategoriesResponse'        => GetCategoriesResponse::class,
				'SaveCategoriesResponse'       => SaveCategoriesResponse::class,
				// Invoices
				'GetInvoicesResponse'          => GetInvoicesResponse::class,
				'GetInvoicesResult'            => GetInvoicesResult::class,
				'InvoiceOrder'                 => InvoiceOrder::class,
				'InvoiceRow'                   => InvoiceRow::class,
				'RowType'                      => RowType::class,
				'ChangeState'                  => ChangeState::class,
				'DeliveryMethod'               => DeliveryMethod::class,
				'Dimension'                    => Dimension::class,
				'UserDefinedDimensions'        => UserDefinedDimensions::class,
				'Category'                     => Category::class,
				// Addresses
				'Addresses'                    => Addresses::class,
				'Address'                      => Address::class,
				// Account Service
				'GetAccountListResponse'       => GetAccountListResponse::class,
				'AccountData'                  => AccountData::class,
				'SaveBundleListResponse'       => SaveBundleListResponse::class,
				'BundleList'                   => BundleList::class,
				'GetTypeListResponse'          => GetTypeListResponse::class,
				'TypeData'                     => TypeData::class,
				// Authenticate Service
				'GetIdentitiesResponse'        => GetIdentitiesResponse::class,
				'Identity'                     => Identity::class,
				'User'                         => User::class,
				'Client'                       => Client::class,
				// Client Service
				'GetCurrencyListResponse'      => GetCurrencyListResponse::class,
				'GetClientInformationResponse' => GetClientInformationResponse::class,
				'Currency'                     => Currency::class,
				'EmailAddress'                 => EmailAddress::class,
				'PhoneNumber'                  => PhoneNumber::class,
				'FaxNumber'                    => FaxNumber::class,
				'AccountingGroup'              => AccountingGroup::class,
				'GetTypeGroupListResponse'     => GetTypeGroupListResponse::class,
				// Company Service
				'GetCompaniesResponse'         => GetCompaniesResponse::class,
				'Company'                      => Company::class,
				'CompanyMap'                   => CompanyMap::class,
				'Relation'                     => Relation::class,
				'SaveCompaniesResponse'        => SaveCompanyResponse::class,
				'SaveRelationsResponse'        => SaveRelationsResponse::class,
				// Person Service
				'GetPersonsResponse'           => GetPersonsResponse::class,
				'PersonItem'                   => PersonItem::class,
				'RelationData'                 => RelationData::class,
				// Transaction Service
				'GetTransactionsResponse'      => GetTransactionsResponse::class,
				'Transaction'                  => Transaction::class,
			],
			'keep_alive'         => Arr::get( $this->options, 'keep_alive', false ),
		];
	}
}
