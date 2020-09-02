<?php


namespace KgBot\SO24\Services;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\AccountService\AccountData;
use KgBot\SO24\Classmaps\AccountService\BundleList;

class AccountService extends BaseService
{
	public function setUp(): string {
		return 'https://webservices.24sevenoffice.com/economy/accountV002/Accountservice.asmx?WSDL';
	}

	protected function getIndexMethod(): string {
		return 'GetAccountList';
	}

	protected function getIndexReturnName() {
		return [];
	}

	protected function getIndexSearchName() {
		return [];
	}

	/**
	 * @return Collection|AccountData
	 * @throws \SoapFault
	 */
	public function getAccountList() {
		return $this->request->call( 'GetAccountList', [] )->getResults();
	}

	/**
	 * @param $data
	 *
	 * @return BundleList
	 * @throws \KgBot\SO24\Exceptions\SO24RequestException
	 */
	public function SaveBundleList( $data ): BundleList {
		return $this->request->call( 'SaveBundleList', $data )->getResults();
	}

	/**
	 * @param $query
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function GetEntryId( $query ) {
		return $this->request->call( 'GetEntryId', $query )->GetEntryIdResult;
	}

	/**
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function GetTypeList() {
		return $this->request->call( 'GetTypeList', [] )->getResults();
	}
}
