<?php


namespace KgBot\SO24\Classmaps;


use Illuminate\Support\Collection;
use KgBot\SO24\Exceptions\RequiredPropertyException;

abstract class BaseResponse implements ResponseInterface
{

	final public function __construct() {
		if ( ! isset( $this->resultsName ) ) {
			throw new RequiredPropertyException( 'You must declare property $resultsName in child class' );
		}
	}

	/**
	 * @return Collection|object
	 */
	public function getResults() {
		$results = collect( $this->{$this->resultsName} )->first();

		if ( is_array( $results ) ) {
			return collect( $results );
		}

		if ( $results === null ) {
			return collect();
		}

		return $results;
	}
}
