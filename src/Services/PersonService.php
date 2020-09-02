<?php


namespace KgBot\SO24\Services;


class PersonService extends BaseService
{
	public function setUp(): string {
		return 'http://webservices.24sevenoffice.com/CRM/Contact/PersonService.asmx?WSDL';
	}

	protected function getIndexMethod(): string {
		return 'GetPersons';
	}

	protected function getIndexSearchName() {
		return 'personSearch';
	}

	protected function getIndexReturnName() {
		return [];
	}
}
