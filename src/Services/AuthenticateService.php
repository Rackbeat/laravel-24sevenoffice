<?php


namespace KgBot\SO24\Services;


class AuthenticateService extends BaseService
{
	protected $serviceUrl = 'http://api.24sevenoffice.com/authenticate/v001/authenticate.asmx?WSDL';

	protected function getIndexMethod(): string {
		return 'GetIdentities';
	}

	protected function getIndexReturnName() {
		[];
	}

	protected function getIndexSearchName() {
		return [];
	}

	/**
	 * @param array $request
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function identities( $request = [] ) {
		return $this->request->call( 'GetIdentities', $request )->getResults();
	}
}
