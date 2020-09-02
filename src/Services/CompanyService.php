<?php


namespace KgBot\SO24\Services;


use KgBot\SO24\Classmaps\CompanyService\Company;

class CompanyService extends BaseService
{
	public function setUp(): string {
		return 'http://api.24sevenoffice.com/CRM/Company/V001/CompanyService.asmx?WSDL';
	}

	protected function getIndexMethod(): string {
		return 'GetCompanies';
	}

	protected function getIndexReturnName() {
		return 'returnProperties';
	}

	protected function getIndexSearchName() {
		return 'searchParams';
	}

	protected function getReturnPropertiesReturnQuery() {
		return [ 'Type', 'EmailAddresses', 'PhoneNumbers', 'Addresses', 'Name', 'ExternalId', 'Id', 'OrganizationNumber', 'CurrencyId' . 'InvoiceLanguage' ];
	}

	/**
	 * @param       $id
	 * @param array $request
	 *
	 * @return Company
	 */
	public function find( $id, array $request = [] ): Company {
		if ( isset( $request['searchParams'] ) ) {
			$request['searchParams']['CompanyId'] = $id;
		} else {
			$request['searchParams'] = [
				'CompanyId' => $id
			];
		}

		return $this->get( $request )->getResults();
	}

	/**
	 * @param array $data
	 *
	 * @return Company
	 * @throws \SoapFault
	 */
	public function createOrUpdate( $data = [] ): Company {

		return $this->request->call( 'SaveCompanies', [
			'companies' => [ 'Company' => $data ],
		] )->getResults();
	}
}
