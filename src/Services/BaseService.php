<?php


namespace KgBot\SO24\Services;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use KgBot\SO24\Utils\Request;

abstract class BaseService implements ServiceInterface
{
	/** @var string */
	protected $serviceUrl;

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
		$cacheKey = '24so-wsdl-' . class_basename( $this );


		if ( Cache::has( $cacheKey ) ) {
			return Cache::get( $cacheKey );
		}

		$file = file_get_contents( $this->serviceUrl );

		if ( $file ) {
			$filename    = '24so_wsdls/' . class_basename( $this ) . '.wsdl';
			$storagePath = storage_path( 'app/' . $filename );

			if ( Storage::disk( 'local' )->put( $filename, $file ) ) {
				Cache::put( $cacheKey, $storagePath, 86400 );

				return $storagePath;
			}
		}

		return $this->serviceUrl;
	}

	/**
	 * @param array $request
	 *
	 * @return mixed
	 * @throws \KgBot\SO24\Exceptions\SO24RequestException
	 */
	public function get( array $request = [] ) {
		$request = $this->getReturnQuery( $request );

		$response = $this->request->call( $this->getIndexMethod(), $request )->getResults();

		if ( is_countable( $response ) ) {
			return $response;
		}

		return collect( [ $response ] );
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
