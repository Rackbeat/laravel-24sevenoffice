<?php


namespace KgBot\SO24\Classmaps\InvoiceService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\ClientService\Currency;
use KgBot\SO24\Classmaps\SingleResource;

class InvoiceOrder extends SingleResource
{
	/** @var int */
	public $OrderId;
	/** @var int */
	public $CustomerId;
	/** @var string */
	public $CustomerName;
	/** @var string */
	public $CustomerDeliveryName;
	/** @var string */
	public $CustomerDeliveryPhone;
	/** @var Collection */
	private $Addresses;
	/** @var string */
	public $OrderStatus;
	/** @var int */
	public $InvoiceId;
	/** @var string */
	public $DateOrdered;
	/** @var string */
	public $DateInvoiced;
	/** @var string */
	public $DateChanged;
	/** @var int */
	public $PaymentTime;
	/** @var string */
	public $CustomerReferenceNo;
	/** @var int */
	public $ProjectId;
	/** @var int */
	public $OurReference;
	/** @var bool */
	public $IncludeVAT;
	/** @var string */
	public $YourReference;
	/** @var float */
	public $OrderTotalIncVat;
	/** @var float */
	public $OrderTotalVat;
	/** @var string */
	public $InvoiceTitle;
	/** @var string */
	public $InvoiceText;
	/** @var string */
	public $Paid;
	/** @var string */
	public $OCR;
	/** @var string */
	public $CustomerOrgNo;
	/** @var Currency */
	public $Currency;
	/** @var int */
	public $PaymentMethodId;
	/** @var float */
	public $PaymentAmount;
	/** @var int */
	public $ProductionManagerId;
	/** @var int */
	public $SalesOpportunityId;
	/** @var int */
	public $TypeOfSaleId;
	/** @var \stdClass */
	public $Distributor;
	/** @var string */
	public $DistributionMethod;
	/** @var int */
	public $DepartmentId;
	/** @var int */
	public $ExternalStatus;
	/** @var string */
	public $InvoiceEmailAddress;
	/** @var Collection */
	private $InvoiceRows;
	/** @var string */
	public $ProductionNumber;
	/** @var string */
	public $DeliveryDate;
	/** @var int */
	public $ReferenceInvoiceId;
	/** @var int */
	public $ReferenceOrderId;
	/** @var string */
	public $ReferenceNumber;
	/** @var bool */
	public $SkipStock;
	/** @var string */
	public $AccrualDate;
	/** @var int */
	public $AccrualLength;
	/** @var float */
	public $RoundFactor;
	/** @var string */
	public $InvoiceTemplateId;
	/** @var string */
	public $VippsNumber;
	/** @var DeliveryMethod */
	public $DeliveryMethod;
	/** @var string */
	public $DeliveryAlternative;
	/** @var bool */
	public $SendToFactoring;
	/** @var float */
	public $Commission;
	/** @var Collection */
	public $UserDefinedDimensions;
	/** @var string */
	public $GLNNumber;
	/** @var int */
	public $CustomerDeliveryId;

	public function getInvoiceRows() {
		$rows = $this->InvoiceRows;

		if ( isset( $rows->InvoiceRow ) ) {
			if ( is_array( $rows->InvoiceRow ) ) {
				return collect( $rows->InvoiceRow );
			}

			return collect( [ $rows->InvoiceRow ] );
		}

		return collect();
	}

	public function getAddresses() {
		return collect( $this->Addresses );
	}

	public function getUserDefinedDimensions() {
		return collect( $this->UserDefinedDimensions );
	}
}