<?php


namespace KgBot\SO24\Classmaps\TimeService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\SingleResource;

class Hour extends SingleResource
{
	/** @var int */
	public $Id;
	/** @var int */
	public $TypeOfWorkId;
	/** @var string */
	public $StartTime;
	/** @var string */
	public $StopTime;
	/** @var float */
	public $TotalHours;
	/** @var float */
	public $TotalHoursInvoice;
	/** @var int */
	public $ContactId;
	/** @var int */
	public $ProjectId;
	/** @var string */
	public $Description;
	/** @var int */
	public $Locked;
	/** @var int */
	public $Approved;
	/** @var int */
	public $ApprovedBy;
	/** @var int */
	public $ProjectTaskId;
	/** @var bool */
	public $NeedApproval;
	/** @var Collection */
	private $Costs;
	/** @var int */
	public $SalaryTypeId;
	/** @var int */
	public $OrderId;
	/** @var float */
	public $Price;
	/** @var string */
	public $InternalNote;
	/** @var int */
	public $CustomerId;
}