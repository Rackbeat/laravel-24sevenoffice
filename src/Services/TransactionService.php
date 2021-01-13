<?php


namespace KgBot\SO24\Services;


class TransactionService extends BaseService
{
	protected $serviceUrl = 'http://api.24sevenoffice.com/Economy/Accounting/V001/TransactionService.asmx?WSDL';

	protected function getIndexMethod(): string {
		return 'GetTransactions';
	}

	protected function getIndexReturnName() {
		return [];
	}

	protected function getIndexSearchName() {
		return 'searchParams';
	}

	/**
	 * @param array $query
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function GetAggregated( $query = [] ): array {
		return $this->request->call( 'GetAggregated', $query )->getResults();
	}
}
