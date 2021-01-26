<?php


namespace KgBot\SO24\Classmaps\AccountService;


use KgBot\SO24\Classmaps\BaseResponse;

class SaveBundleListResponse extends BaseResponse
{
	protected $resultsName = 'SaveBundleListResult';

	public function getResults() {
		return $this->{$this->resultsName};
	}
}