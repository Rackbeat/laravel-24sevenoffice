<?php


namespace KgBot\SO24\Services;


class TransactionService extends BaseService
{
	public function setUp(): string {
		return 'https://api.24sevenoffice.com/Economy/Accounting/V001/TransactionService.asmx?WSDL';
	}

	/**
	 * @param array $query
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function GetAggregated( $query = [] ): array {
		$response = $this->request->call( 'GetAggregated', $query )->GetAggregatedResult;

		$response = $response->AggregatedData ?? [];

		return is_array( $response ) ? $response : [ $response ];
	}

	/**
	 * @param array $query
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function GetTransactions( $query = [] ): array {
		$response = $this->request->call( 'GetTransactions', $query )->GetTransactionsResult;

		$response = $response->Transaction ?? [];

		return is_array( $response ) ? $response : [ $response ];
	}
}
