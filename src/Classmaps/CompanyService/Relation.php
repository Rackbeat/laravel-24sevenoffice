<?php


namespace KgBot\SO24\Classmaps\CompanyService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\SingleResource;

class Relation extends SingleResource
{
	/** @var int */
	public $ContactId;
	/** @var int */
	public $CompanyId;
	/** @var string */
	public $FirstName;
	/** @var string */
	public $LastName;
	/** @var string */
	public $Role;
	/** @var int */
	public $RoleId;
	/** @var Collection */
	private $PhoneNumbers;
	/** @var Collection */
	private $EmailAddresses;
	/** @var string */
	public $Fax;

	public function getPhoneNumbers() {
		return collect( $this->PhoneNumbers ?? [] );
	}

	public function getEmailsAddresses() {
		return collect( $this->EmailAddresses ?? [] );
	}
}