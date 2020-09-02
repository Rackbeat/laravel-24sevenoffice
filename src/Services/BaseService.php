<?php


namespace KgBot\SO24\Services;


use Illuminate\Support\Str;
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

	public function get( $request ) {
		$request = $this->getReturnQuery( $request );

		return $this->request->call( $this->getIndexMethod(), $request )->getResults();
	}

	/**
	 * Setup return query based on passed request and default query if any
	 *
	 * @param array $request
	 *
	 * @return array
	 */
	protected function getReturnQuery( array $request = [] ): array {
		$returnQuery = $this->getIndexReturnName();
		if ( ! is_array( $returnQuery ) ) {
			$returnQuery = [ $returnQuery ];
		}

		foreach ( $returnQuery as $query ) {
			if ( ! isset( $request[ $query ] ) && method_exists( $this, $method = "get" . ucfirst( Str::camel( $query ) ) . "ReturnQuery" ) ) {
				$request[ $query ] = $this->$method();
			}
		}

		return $request;
	}

	abstract protected function getIndexMethod(): string;

	/**
	 * This method should return name of search query
	 * Eg: When we want to search products we need to set this to "searchParams", like stated in here https://api.24sevenoffice.com/Logistics/Product/V001/ProductService.asmx?op=GetProducts
	 *
	 * @return string|array
	 */
	abstract protected function getIndexSearchName();

	/**
	 * This method should return name of return query
	 * Eg: When we want to search products we need to set this to "returnProperties", like stated in here https://api.24sevenoffice.com/Logistics/Product/V001/ProductService.asmx?op=GetProducts
	 *
	 * Or if there is multiple return query allowed we should return this as array of string
	 * Eg: When we search for invoices we need to define it like this ["invoiceReturnProperties", "rowReturnProperties"], like stated in here https://api.24sevenoffice.com/Economy/InvoiceOrder/V001/InvoiceService.asmx?op=GetInvoices
	 *
	 * @return string|array
	 */
	abstract protected function getIndexReturnName();
}
