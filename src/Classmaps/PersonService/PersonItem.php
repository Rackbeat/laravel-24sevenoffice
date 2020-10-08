<?php


namespace KgBot\SO24\Classmaps\PersonService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\Addresses\Address;
use KgBot\SO24\Classmaps\ClientService\FaxNumber;
use KgBot\SO24\Classmaps\SingleResource;

class PersonItem extends SingleResource
{
	/** @var string */
	public $ConsumerPersonNo;
	/** @var int */
	public $Id;
	/** @var int */
	public $EmployeeId;
	/** @var string */
	public $FirstName;
	/** @var string */
	public $LastName;
	/** @var string */
	public $Url;
	/** @var string */
	public $Country;
	/** @var string */
	public $Comment;
	/** @var Address */
	public $PostAddress;
	/** @var FaxNumber */
	public $FaxNumber;
	/** @var Collection */
	private $PhoneNumbers;
	/** @var Collection */
	private $EmailAddresses;
	/** @var string */
	public $DateChanged;
	/** @var string */
	public $DateOfBirth;
	/** @var string */
	public $WebUserName;
	/** @var string */
	public $WebPassword;
	/** @var string */
	public $Nickname;
	/** @var string */
	public $PersonalStatus;
	/** @var string */
	public $Workplace;
	/** @var string */
	public $Department;
	/** @var string */
	public $WorkPosition;
	/** @var Collection */
	private $RelationData;
	/** @var float */
	public $HourCost;
	/** @var int */
	public $CustomerId;
	/** @var bool */
	public $IsPrivate;

	public function getRelationData() {
		return collect( $this->RelationData ?? [] );
	}
}