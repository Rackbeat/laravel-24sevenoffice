<?php


namespace KgBot\SO24\Services;


class TimeService extends BaseService
{
	public function setUp(): string {
		return 'http://webservices.24sevenoffice.com/timesheet/v001/timeservice.asmx?WSDL';
	}

	protected function getIndexMethod(): string {
		return 'GetHours';
	}

	protected function getIndexReturnName() {
		return [];
	}

	protected function getIndexSearchName() {
		return 'hs';
	}
}
