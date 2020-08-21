<?php


namespace KgBot\SO24\Services;

use KgBot\SO24\Exceptions\ProductGroupTransferException;
use KgBot\SO24\Exceptions\ProductTransferException;

class ProductService extends BaseService
{
	public function setUp(): string {
		return 'http://api.24sevenoffice.com/Logistics/Product/V001/ProductService.asmx?WSDL';
	}

	/**
	 * @param $request
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function get( $request ) {
		$response = (array) $this->request->call( 'GetProducts', $request )->GetProductsResult;

		return ( isset( $response['Product'] ) && is_array( $response['Product'] ) ) ? $response['Product'] : $response;
	}

	public function find( $id, array $request = [] ) {
		if ( isset( $request['searchParams'] ) ) {
			$request['searchParams']['Id'] = $id;
		} else {
			$request['searchParams'] = [
				'Id' => $id
			];
		}

		if ( ! isset( $request['returnProperties'] ) ) {
			$request['returnProperties'] = [ 'Name', 'No', 'Price', 'Id', 'CategoryId', 'InPrice' ];
		}

		$response = (array) $this->get( $request );

		return $response['Product'] ?? $response;
	}

	/**
	 * @param array $data
	 *
	 * @return mixed
	 * @throws ProductTransferException
	 * @throws \SoapFault
	 */
	public function createOrUpdate( $data = [] ) {
		$response = $this->request->call( 'SaveProducts', [
			'products' => [ 'Product' => $data ],
		] );

		if ( ! isset( $response->SaveProductsResult ) ) {
			throw new ProductTransferException( json_encode( $response ) );
		}

		$response = $response->SaveProductsResult->Product;

		if ( isset( $response->APIException ) ) {
			throw new ProductTransferException( '24SO API Exception: ' . $response->APIException->Message, 500 );
		}

		return (object) $response;
	}

	/**
	 * @param array $returnProperties
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function getCategories( $returnProperties = [] ): array {

		$response = (array) $this->request->call( 'GetCategories', [
			'returnProperties' => $returnProperties
		] )->GetCategoriesResult;

		return ( is_array( $response['Category'] ) ) ? $response['Category'] : $response;
	}

	public function createCategory( $data ) {
		$response = $this->request->call( 'SaveCategories', [
			'categories' => [ 'Category' => $data ],
		] );

		if ( ! isset( $response->SaveCategoriesResult ) ) {
			throw new ProductGroupTransferException( json_encode( $response ) );
		}

		$response = $response->SaveCategoriesResult->Category;

		if ( isset( $response->APIException ) ) {
			throw new ProductGroupTransferException( '24SO API Exception: ' . $response->APIException->Message, 500 );
		}

		return (object) $response;
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
