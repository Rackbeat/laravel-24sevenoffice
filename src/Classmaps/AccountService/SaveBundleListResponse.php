<?php


namespace KgBot\SO24\Classmaps\AccountService;


use KgBot\SO24\Classmaps\AuthenticateService\Client;
use KgBot\SO24\Classmaps\BaseResponse;

class SaveBundleListResponse extends BaseResponse
{
	protected $resultsName = 'SaveBundleListResult';

	/**
	 * @return Client
	 */
	public function getResults(): Client {
		return $this->{$this->resultsName};
	}
}