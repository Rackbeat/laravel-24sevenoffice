<?php


namespace KgBot\SO24\Classmaps\AuthenticateService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\SingleResource;

class Client extends SingleResource
{
	/** @var int */
	public $UserId;
	/** @var string */
	public $Name;
	/** @var string */
	public $Country;
	/** @var string */
	public $FactoringClientNo;
	/** @var string */
	public $FactoringText;
	/** @var bool */
	public $IsUsingFactoring;
	/** @var int */
	public $ReminderDays;
	/** @var int */
	public $ReminderDays2;
	/** @var int */
	public $ReminderDays3;
	/** @var Collection */
	private $AddressList;
	/** @var Collection */
	private $FaxNumberList;
	/** @var Collection */
	private $PhoneNumberList;
	/** @var Collection */
	private $EmailAddressList;
	/** @var string */
	public $BankAccount;
	/** @var string */
	public $IBAN;
	/** @var string */
	public $Swift;
	/** @var string */
	public $OrganizationNumber;
	/** @var string */
	public $DefaultCurrency;
	/** @var int */
	public $ResellerId;
	/** @var string */
	public $ResellerName;

	public function getAddressList() {
		return collect( $this->AddressList ?? [] );
	}

	public function getFaxNumberList() {
		return collect( $this->FaxNumberList ?? [] );
	}

	public function getPhoneNumberList() {
		return collect( $this->PhoneNumberList ?? [] );
	}

	public function getEmailAddressList() {
		return collect( $this->EmailAddressList ?? [] );
	}
}