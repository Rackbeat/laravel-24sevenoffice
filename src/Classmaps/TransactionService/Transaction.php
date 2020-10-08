<?php


namespace KgBot\SO24\Classmaps\TransactionService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\SingleResource;

class Transaction extends SingleResource
{
	/** @var string */
	public $Date;
	/** @var int */
	public $AccountNo;
	/** @var string */
	public $Currency;
	/** @var float */
	public $CurrencyRate;
	/** @var int */
	public $CurrencyUnit;
	/** @var float */
	public $Amount;
	/** @var int */
	public $StampNo;
	/** @var int */
	public $Period;
	/** @var int */
	public $TransactionTypeId;
	/** @var string */
	public $Comment;
	/** @var int */
	public $TransactionNo;
	/** @var int */
	public $VatCode;
	/** @var int */
	public $Id;
	/** @var int */
	public $LinkId;
	/** @var string */
	public $InvoiceNo;
	/** @var int */
	public $SequenceNo;
	/** @var string */
	public $SystemType;
	/** @var string */
	public $DueDate;
	/** @var Collection */
	private $Dimensions;
	/** @var string */
	public $RegistrationDate;
	/** @var string */
	public $OCR;
	/** @var bool */
	public $Open;
	/** @var bool */
	public $Hidden;
	/** @var string */
	public $DateChanged;
	/** @var bool */
	public $HasVatDividend;
	/** @var float */
	public $VatDividend;

	public function getDimensions() {
		return collect( $this->Dimensions ?? [] );
	}
}