<?php


namespace KgBot\SO24\Services;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\ClientService\AccountingGroup;
use KgBot\SO24\Classmaps\ClientService\Currency;

class ClientService extends BaseService
{
	public function setUp(): string {
		return 'https://api.24sevenoffice.com/Client/V001/ClientService.asmx?WSDL';
	}

	protected function getIndexMethod(): string {
		return 'GetUsers';
	}

	protected function getIndexReturnName() {
		return [];
	}

	protected function getIndexSearchName() {
		return [];
	}

	/**
	 * @param array $request
	 *
	 * @return Collection|Currency
	 * @throws \SoapFault
	 */
	public function currencies( $request = [] ) {
		return $this->request->call( 'GetCurrencyList', $request )->getResults();
	}

	/**
	 * @param $request
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function client_info( $request = [] ) {
		return $this->request->call( 'GetClientInformation', $request )->getResults();
	}

	/**
	 * @param array $request
	 *
	 * @return Collection|AccountingGroup
	 * @throws \SoapFault
	 */
	public function type_group_lists( $request = [] ) {
		return $this->request->call( 'GetTypeGroupList', $request )->getResults();
	}
}
