<?php


namespace KgBot\SO24\Services;

use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\ProductService\Categories\Category;
use KgBot\SO24\Classmaps\ProductService\Product;
use KgBot\SO24\Classmaps\SingleResource;
use KgBot\SO24\Exceptions\ProductGroupTransferException;
use KgBot\SO24\Exceptions\ProductTransferException;

class ProductService extends BaseService
{
	protected $serviceUrl = 'http://api.24sevenoffice.com/Logistics/Product/V001/ProductService.asmx?WSDL';

	protected function getIndexMethod(): string {
		return 'GetProducts';
	}

	protected function getIndexReturnName() {
		return 'returnProperties';
	}

	protected function getIndexSearchName() {
		return "searchParams";
	}

	protected function getReturnPropertiesReturnQuery() {
		return [ 'Name', 'No', 'Price', 'Id', 'CategoryId', 'InPrice' ];
	}

	/**
	 * @param       $id
	 * @param array $request
	 *
	 * @return SingleResource|null
	 * @throws \SoapFault
	 */
	public function find( $id, array $request = [] ): ?SingleResource {
		if ( isset( $request['searchParams'] ) ) {
			$request['searchParams']['Id'] = $id;
		} else {
			$request['searchParams'] = [
				'Id' => $id
			];
		}

		$response = $this->get( $request );

		if ( is_countable( $response ) ) {
			if ( $response instanceof Collection ) {
				return $response->first();
			}
			if ( isset( $response[0] ) ) {
				return $response[0];
			}
		}

		return $response;
	}

	/**
	 * @param array $data
	 *
	 * @return Product
	 * @throws ProductTransferException
	 * @throws \SoapFault
	 */
	public function createOrUpdate( $data = [] ): Product {
		$response = $this->request->call( 'SaveProducts', [
			'products' => [ 'Product' => $data ],
		] )->getResults();

		if ( isset( $response->APIException ) && ! empty( $response->APIException ) ) {
			throw new ProductTransferException( '24SO API Exception: ' . $response->APIException->Message, 500 );
		}

		return $response;
	}

	/**
	 * @param array $returnProperties
	 *
	 * @return Collection|Category
	 * @throws \KgBot\SO24\Exceptions\SO24RequestException
	 */
	public function getCategories( $returnProperties = [] ) {

		return $this->request->call( 'GetCategories', [
			'returnProperties' => $returnProperties
		] )->getResults();
	}

	/**
	 * @param $data
	 *
	 * @return Category
	 * @throws ProductGroupTransferException
	 * @throws \KgBot\SO24\Exceptions\SO24RequestException
	 */
	public function createCategory( $data ): Category {
		$response = $this->request->call( 'SaveCategories', [
			'categories' => [ 'Category' => $data ],
		] )->getResults();

		if ( isset( $response->APIException ) ) {
			throw new ProductGroupTransferException( '24SO API Exception: ' . $response->APIException->Message, 500 );
		}

		return $response;
	}

	/**
	 * @param $productId
	 * @param $stock
	 *
	 * @return false|string
	 * @throws \SoapFault
	 */
	public function set_stock( $productId, $stock ) {
		return json_encode( $this->request->call( 'SetStockQuantity', [
			'productId'     => $productId,
			'stockQuantity' => $stock,
		] ) );
	}
}
