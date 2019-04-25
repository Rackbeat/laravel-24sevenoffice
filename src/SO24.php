<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 31.3.18.
 * Time: 15.12
 */

namespace KgBot\SO24;

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
		return $this->initRequest( $username, $password, $api_token, $options, $headers );
	}

	/**
	 * @param       $token
	 * @param array $options
	 * @param array $headers
	 */
	private function initRequest( $username, $password, $api_token, $options = [], $headers = [] ) {
		return new Request( $username, $password, $api_token, $options, $headers );
	}

	public function set_service( $service ) {

		$this->request->set_service( $service );

		return $this;
	}

	public function call( $action, $request ) {

		return $this->request->call( $action, $request );
	}
}