<?php


namespace KgBot\SO24\Classmaps\TransactionService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\Dimension;
use KgBot\SO24\Classmaps\SingleResource;

/** @property Collection $Periods */
class AggregatedData extends SingleResource
{
	/** @var float */
	public $IncomingBalance;
	/** @var float */
	public $TotalBalance;
	/** @var Collection */
	private $Periods;
	/** @var Dimension */
	public $Dimension;
	/** @var int */
	public $AccountNo;

	public function getPeriods() {
		return collect( $this->Periods ?? [] );
	}
}