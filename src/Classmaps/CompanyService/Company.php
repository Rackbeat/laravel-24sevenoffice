<?php


namespace KgBot\SO24\Classmaps\CompanyService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\SingleResource;

class Company extends SingleResource
{
	/** @var int */
	public $Id;
	/** @var string */
	public $ExternalId;
	/** @var string */
	public $OrganizationNumber;
	/** @var string */
	public $Name;
	/** @var string */
	public $FirstName;
	/** @var string */
	public $NickName;
	/** @var Collection */
	public $Addresses;
	/** @var Collection */
	private $PhoneNumbers;
	/** @var Collection */
	private $EmailAddresses;
	/** @var string */
	public $Url;
	/** @var string */
	public $Country;
	/** @var string */
	public $Note;
	/** @var string */
	public $InvoiceLanguage;
	/** @var string */
	public $Type;
	/** @var string */
	public $Username;
	/** @var string */
	public $Password;
	/** @var string */
	public $IncorporationDate;
	/** @var string */
	public $DateCreated;
	/** @var int */
	public $Status;
	/** @var int */
	public $PriceList;
	/** @var int */
	public $Owner;
	/** @var string */
	public $BankAccountNo;
	/** @var string */
	public $BankAccountType;
	/** @var string */
	public $BankAccountCountry;
	/** @var string */
	public $BankAccountBic;
	/** @var int */
	public $TermsOfDeliveryId;
	/** @var int */
	public $AccountDebit;
	/** @var int */
	public $AccountCredit;
	/** @var float */
	public $Discount;
	/** @var int */
	public $TypeGroup;
	/** @var float */
	public $ShareCapital;
	/** @var int */
	public $NumberOfEmployees;
	/** @var float */
	public $Turnover;
	/** @var float */
	public $Profit;
	/** @var int */
	public $IndustryId;
	/** @var int */
	public $MemberNo;
	/** @var string */
	public $DateChanged;
	/** @var bool */
	public $BlockInvoice;
	/** @var Collection */
	private $Relations;
	/** @var Collection */
	private $Maps;
	/** @var string */
	public $DistributionMethod;
	/** @var string */
	public $CurrencyId;
	/** @var int */
	public $PaymentTime;
	/** @var int */
	public $GlnNumber;
	/** @var bool */
	public $Factoring;
	/** @var int */
	public $LedgerCustomerAccount;
	/** @var int */
	public $LedgerSupplierAccount;
	/** @var string */
	public $VatNumber;
	/** @var bool */
	public $Private;

	public function getAddresses() {
		return collect( $this->Addresses );
	}

	public function getPhoneNumbers() {
		return collect( $this->PhoneNumbers );
	}

	public function getEmailAddresses() {
		return collect( $this->EmailAddresses );
	}

	public function getRelations() {
		return collect( $this->Relations );
	}

	public function getMaps() {
		return collect( $this->Maps );
	}
}