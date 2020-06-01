<?php


namespace KgBot\SO24\Services;


use KgBot\SO24\Utils\Request;

abstract class BaseService implements ServiceInterface
{
	/** @var Request */
	protected $request;

	/** @var \SoapClient */
	protected $service;

	public function __construct( Request $request ) {
		$this->request          = $request;
		$this->request->service = $this->setUp();
		$this->request->service();
	}

	public function setUp(): string {
		throw new \Exception( 'You must override this method in your child classes' );
	}
}
