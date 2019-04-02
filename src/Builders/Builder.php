<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 31.3.18.
 * Time: 17.00
 */

namespace KgBot\SO24\Builders;

use KgBot\SO24\Utils\Model;
use KgBot\SO24\Utils\Request;


class Builder
{
	protected $entity;
	protected $resource_name;
	/** @var Model */
	protected $model;
	protected $request;

	public function __construct( Request $request ) {
		$this->request = $request;
	}

	public function find( $action, $id ) {
		return $this->request->handleWithExceptions( function () use ( $action, $id ) {

			$response = $this->get( $action, [ [ 'field' => 'Id', 'value' => $id ] ] );

			return new $this->model( $this->request, $response[0] );
		} );
	}

	/**
	 * @param array $filters
	 *
	 * @return \Illuminate\Support\Collection|Model[]
	 */
	public function get( $action = null, $filters = [], $properties = [] ) {
		$searchParams     = $this->parseFilters( $filters );
		$returnProperties = $this->parseReturnProperties( $properties );

		return $this->request->handleWithExceptions( function () use ( $action, $searchParams, $returnProperties ) {

			$request = [

				'searchParams'     => $searchParams,
				'returnProperties' => $returnProperties,
			];

			$this->request->set_service( $this->entity );
			$response = $this->request->call( $action, $request );

			$items = $this->parseResponse( $response );

			return $items;
		} );
	}

	protected function parseFilters( $filters ) {
		$searchParams = [];
		if ( count( $filters ) > 0 ) {

			foreach ( $filters as $filter ) {

				$searchParams[ $filter['field'] ] = $filter['value'];

			}
		}

		return $searchParams;
	}

	protected function parseReturnProperties( $properties ) {

		$returnProperties = [];
		if ( count( $properties ) > 0 ) {

			foreach ( $properties as $property ) {

				$returnProperties[] = $property;

			}
		}

		return $returnProperties;
	}

	protected function parseResponse( $response ) {
		$fetchedItems = collect( $response[0] );
		$items        = collect( [] );

		foreach ( $fetchedItems as $index => $item ) {


			/** @var Model $model */
			$model = new $this->model( $this->request, $item );

			$items->push( $model );

		}

		return $items;
	}

	public function create( $action, $data ) {
		$data = [
			$this->resource_name => $data,
		];

		return $this->request->handleWithExceptions( function () use ( $action, $data ) {

			$this->request->set_service( $this->entity );
			$response = $this->request->call( $action, $data );

			return new $this->model( $this->request, $response[0] );
		} );
	}

	public function getEntity() {
		return $this->entity;
	}

	public function setEntity( $new_entity ) {
		$this->entity = $new_entity;

		return $this->entity;
	}
}