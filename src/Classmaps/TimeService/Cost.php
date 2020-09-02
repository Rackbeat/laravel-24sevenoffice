<?php


namespace KgBot\SO24\Classmaps\TimeService;


use KgBot\SO24\Classmaps\SingleResource;

class Cost extends SingleResource
{
	/** @var int */
	public $Id;
	/** @var string */
	public $DateRegistered;
	/** @var int */
	public $ProductId;
	/** @var int */
	public $SupplierCompanyId;
	/** @var float */
	public $Quantity;
	/** @var float */
	public $InPrice;
	/** @var string */
	public $Description;
	/** @var int */
	public $ProjectId;
	/** @var float */
	public $Price;
	/** @var int */
	public $CustomerId;
}