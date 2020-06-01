<?php


namespace KgBot\SO24\Services;


class ClientService extends BaseService
{
	public function setUp(): string {
		return 'https://api.24sevenoffice.com/Client/V001/ClientService.asmx?WSDL';
	}

	/**
	 * @param array $request
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function currencies( $request = [] ): array {
		$response = $this->request->call( 'GetCurrencyList', $request )->GetCurrencyListResult;

		$response = $response->Currency ?? [];

		return is_array( $response ) ? $response : [ $response ];
	}

	/**
	 * @param $request
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function client_info( $request = [] ) {
		return $this->request->call( 'GetClientInformation', $request )->GetClientInformationResult;
	}

	/**
	 * @param array $request
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function type_group_lists( $request = [] ): array {
		$response = $this->request->call( 'GetTypeGroupList', $request )->GetTypeGroupListResult;

		$response = $response->AccountingGroup ?? [];

		return is_array( $response ) ? $response : [ $response ];
	}
}
