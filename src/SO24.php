<?php

namespace KgBot\SO24;

use KgBot\SO24\Contracts\BucketContract;
use KgBot\SO24\Contracts\SessionContract;
use KgBot\SO24\Services\AccountService;
use KgBot\SO24\Services\AuthenticateService;
use KgBot\SO24\Services\ClientService;
use KgBot\SO24\Services\CompanyService;
use KgBot\SO24\Services\InvoiceService;
use KgBot\SO24\Services\PersonService;
use KgBot\SO24\Services\ProductService;
use KgBot\SO24\Services\TimeService;
use KgBot\SO24\Services\TransactionService;
use KgBot\SO24\Utils\Request;

class SO24
{
	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * SO24 constructor.
	 *
	 * @param null            $username
	 * @param null            $password
	 * @param null            $api_token
	 * @param BucketContract  $bucket
	 * @param SessionContract $session
	 * @param null            $identity
	 * @param array           $options
	 *
	 * @throws Exceptions\SO24RequestException
	 */
	public function __construct( $username, $password, $api_token, BucketContract $bucket, SessionContract $session, $identity = null, $options = [] ) {
		$this->request = $this->initRequest( $username, $password, $api_token, $bucket, $session, $identity, $options );
	}

	/**
	 * @param                     $username
	 * @param                     $password
	 * @param                     $api_token
	 * @param BucketContract      $bucket
	 * @param SessionContract     $session
	 * @param                     $identity
	 * @param array               $options
	 *
	 * @return Request
	 * @throws Exceptions\SO24RequestException
	 */
	private function initRequest( $username, $password, $api_token, BucketContract $bucket, SessionContract $session, $identity, $options = [] ): Request {
		return new Request( $username, $password, $api_token, $bucket, $session, $identity, $options );
	}

	/**
	 * @return InvoiceService
	 */
	public function invoices(): InvoiceService {
		return new InvoiceService( $this->request );
	}

	/**
	 * @return ProductService
	 */
	public function products(): ProductService {
		return new ProductService( $this->request );
	}

	/**
	 * @return CompanyService
	 */
	public function companies(): CompanyService {
		return new CompanyService( $this->request );
	}

	/**
	 * @return PersonService
	 */
	public function persons(): PersonService {
		return new PersonService( $this->request );
	}

	/**
	 * @return ClientService
	 */
	public function clients(): ClientService {
		return new ClientService( $this->request );
	}

	/**
	 * @return AuthenticateService
	 */
	public function authenticate_service(): AuthenticateService {
		return new AuthenticateService( $this->request );
	}

	/**
	 * @return AccountService
	 */
	public function account_service(): AccountService {
		return new AccountService( $this->request );
	}

	/**
	 * @return TransactionService
	 */
	public function transaction_service(): TransactionService {
		return new TransactionService( $this->request );
	}

	/**
	 * @return TimeService
	 */
	public function time_service(): TimeService {
		return new TimeService( $this->request );
	}
}
