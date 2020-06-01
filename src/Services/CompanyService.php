<?php


namespace KgBot\SO24\Services;


class CompanyService extends BaseService
{
	public function setUp(): string {
		return 'http://api.24sevenoffice.com/CRM/Company/V001/CompanyService.asmx?WSDL';
	}

	/**
	 * @param array $request
	 *
	 * @return array|mixed
	 * @throws \SoapFault
	 */
	public function get( $request = [] ) {
		$response = (array) $this->request->call( 'GetCompanies', $request )->GetCompaniesResult;

		return ( isset( $response['Company'] ) && is_array( $response['Company'] ) ) ? $response['Company'] : $response;
	}

	/**
	 * @param       $id
	 * @param array $request
	 *
	 * @return array|mixed
	 * @throws \SoapFault
	 */
	public function find( $id, array $request = [] ) {
		if ( isset( $request['searchParams'] ) ) {
			$request['searchParams']['CompanyId'] = $id;
		} else {
			$request['searchParams'] = [
				'CompanyId' => $id
			];
		}

		if ( ! isset( $request['returnProperties'] ) ) {
			$request['returnProperties'] = [ 'Type', 'EmailAddresses', 'PhoneNumbers', 'Addresses', 'Name', 'ExternalId', 'Id', 'OrganizationNumber', 'CurrencyId' . 'InvoiceLanguage' ];
		}

		$response = (array) $this->get( $request );

		return $response['Company'] ?? $response;
	}

	/**
	 * @param array $data
	 *
	 * @return mixed
	 * @throws \SoapFault
	 */
	public function createOrUpdate( $data = [] ) {

		$response = (object) $this->request->call( 'SaveCompanies', [
			'companies' => [ 'Company' => $data ],
		] );

		return (object) $response->SaveCompaniesResult->Company;
	}
}
