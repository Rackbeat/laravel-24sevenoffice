<?php


namespace KgBot\SO24\Classmaps\InvoiceService;


use KgBot\SO24\Classmaps\SingleResource;

class InvoiceRow extends SingleResource
{
	/** @var int */
	public $ProductId;
	/** @var string */
	public $ProductNo;
	/** @var int */
	public $RowId;
	/** @var float */
	public $VatRate;
	/** @var float */
	public $Price;
	/** @var string */
	public $Name;
	/** @var float */
	public $DiscountRate;
	/** @var float */
	public $Quantity;
	/** @var float */
	public $QuantityDelivered;
	/** @var float */
	public $QuantityOrdered;
	/** @var float */
	public $QuantityRest;
	/** @var float */
	public $Cost;
	/** @var float */
	public $InPrice;
	/** @var int */
	public $SequenceNumber;
	/** @var bool */
	public $Hidden;
	/** @var RowType */
	public $Type;
	/** @var string */
	public $AccrualDate;
	/** @var int */
	public $AccrualLength;
	/** @var ChangeState */
	public $ChangeState;
	/** @var int */
	public $TypeGroupId;
	/** @var bool */
	public $AccountProject;
	/** @var int */
	public $DepartmentId;
	/** @var int */
	public $ProjectId;
}