<?php


namespace KgBot\SO24\Classmaps\ClientService;


use KgBot\SO24\Classmaps\AuthenticateService\Client;
use KgBot\SO24\Classmaps\BaseResponse;

class GetClientInformationResponse extends BaseResponse
{
	protected $resultsName = 'GetClientInformationResult';

	/**
	 * @return Client
	 */
	public function getResults(): Client {
		return $this->{$this->resultsName};
	}
}