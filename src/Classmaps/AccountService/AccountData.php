<?php


namespace KgBot\SO24\Classmaps\AccountService;


use KgBot\SO24\Classmaps\SingleResource;

class AccountData extends SingleResource
{
	/** @var int */
	public $AccountId;
	/** @var int */
	public $AccountNo;
	/** @var string */
	public $AccountName;
	/** @var int */
	public $AccountTax;
	/** @var int */
	public $TaxNo;
}