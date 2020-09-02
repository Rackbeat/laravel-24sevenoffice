<?php


namespace KgBot\SO24\Classmaps;


class SingleResource
{
	public $APIException;

	public function __get( $name ) {
		if ( method_exists( $this, $method = 'get' . ucfirst( $name ) ) ) {
			return $this->$method();
		}

		if ( property_exists( $this, $name ) ) {
			return $this->{$name};
		}

		throw new \Exception( 'Property ' . $name . ' does not exist.' );
	}
}