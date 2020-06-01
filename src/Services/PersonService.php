<?php


namespace KgBot\SO24\Services;


class PersonService extends BaseService
{
	public function setUp(): string {
		return 'http://webservices.24sevenoffice.com/CRM/Contact/PersonService.asmx?WSDL';
	}

	public function get( $request = [] ) {
		$response = $this->request->call( 'GetPersons', [ 'personSearch' => $request ] )->GetPersonsResult->PersonItem;

		return is_array( $response ) ? $response : [ $response ];
	}
}
