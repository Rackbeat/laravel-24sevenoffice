<?php


namespace KgBot\SO24\Services;


class AuthenticateService extends BaseService
{
	public function setUp(): string {
		return 'http://api.24sevenoffice.com/authenticate/v001/authenticate.asmx?WSDL';
	}

	/**
	 * @param array $request
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function identities( $request = [] ) {
		$response = $this->request->call( 'GetIdentities', $request )->GetIdentitiesResult;

		return ( isset( $response->Identity ) && is_array( $response->Identity ) ) ? $response->Identity : $response;
	}
}
