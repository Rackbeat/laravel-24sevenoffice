<?php


namespace KgBot\SO24\Services;


class PersonService extends BaseService
{
	protected $serviceUrl = 'http://webservices.24sevenoffice.com/CRM/Contact/PersonService.asmx?WSDL';

	protected function getIndexMethod(): string {
		return 'GetPersons';
	}

	protected function getIndexSearchName() {
		return 'personSearch';
	}

	protected function getIndexReturnName() {
		return [];
	}

	public function getPersonId( $consumerPersonNo ) {
		return $this->request->call( 'GetPersonId', [
			'consumerPersonNo' => $consumerPersonNo
		] )->getResults();
	}

	public function savePerson( array $data ) {
		return $this->request->call( 'SavePerson', [
			'personItem' => $data
		] )->getResults();
	}

	public function details( array $request = [] ) {
		return $this->request->call( 'GetPersonsDetailed', $request )->getResults();
	}
}
