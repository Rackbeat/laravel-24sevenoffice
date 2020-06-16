<?php


namespace KgBot\SO24\Services;


class AccountService extends BaseService
{
	public function setUp(): string {
		return 'https://webservices.24sevenoffice.com/economy/accountV002/Accountservice.asmx?WSDL';
	}

	/**
	 * @return array
	 * @throws \SoapFault
	 */
	public function getAccountList(): array {
		$response = $this->request->call( 'GetAccountList', [] )->GetAccountListResult;

		$response = $response->AccountData ?? [];

		return is_array( $response ) ? $response : [ $response ];
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function SaveBundleList( $data ) {
		return $this->request->call( 'SaveBundleList', $data )->SaveBundleListResult;
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
		return $this->request->call( 'GetTypeList', [] )->GetTypeListResult;
	}
}
