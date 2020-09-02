<?php


namespace KgBot\SO24\Services;


class TimeService extends BaseService
{
	public function setUp(): string {
		return 'http://webservices.24sevenoffice.com/timesheet/v001/timeservice.asmx?WSDL';
	}

	public function hours( $request = [] ) {
		return $this->request->call( 'GetHours', [ 'hs' => $request ] );
	}
}
