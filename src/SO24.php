<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 31.3.18.
 * Time: 15.12
 */

namespace KgBot\SO24;

use KgBot\SO24\Builders\CustomerBuilder;
use KgBot\SO24\Builders\CustomerGroupBuilder;
use KgBot\SO24\Builders\OrderBuilder;
use KgBot\SO24\Builders\ProductBuilder;
use KgBot\SO24\Utils\Request;

class SO24
{
	/**
	 * @var $request Request
	 */
	protected $request;

	/**
	 * Rackbeat constructor.
	 *
	 * @param null  $token   API token
	 * @param array $options Custom Guzzle options
	 * @param array $headers Custom Guzzle headers
	 */
	public function __construct( $username = null, $password = null, $api_token = null, $options = [], $headers = [] ) {
		$this->initRequest( $username, $password, $api_token, $options, $headers );
	}

	/**
	 * @param       $token
	 * @param array $options
	 * @param array $headers
	 */
	private function initRequest( $username, $password, $api_token, $options = [], $headers = [] ) {
		$this->request = new Request( $username, $password, $api_token, $options, $headers );
	}

	/**
	 * @return \KgBot\SO24\Builders\OrderBuilder
	 */
	public function orders() {
		return new OrderBuilder( $this->request );
	}

	/**
	 * @return \KgBot\SO24\Builders\CustomerBuilder
	 */
	public function customers() {
		return new CustomerBuilder( $this->request );
	}

	/**
	 * @return \KgBot\SO24\Builders\ProductBuilder
	 */
	public function products() {
		return new ProductBuilder( $this->request );
	}

	/**
	 * @return \KgBot\SO24\Builders\CustomerGroupBuilder
	 */
	public function customer_groups() {

		return new CustomerGroupBuilder( $this->request );
	}
}