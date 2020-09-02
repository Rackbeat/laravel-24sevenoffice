<?php


namespace KgBot\SO24\Classmaps\InvoiceService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\SingleResource;

class InvoiceOrder extends SingleResource
{
	public $OrderId;
	public $CustomerId;
	public $CustomerName;
	public $CustomerDeliveryName;
	public $CustomerDeliveryPhone;
	/** @var Collection */
	private $Addresses;
	public  $OrderStatus;
	public  $InvoiceId;
	public  $DateOrdered;
	public  $DateInvoiced;
	public  $DateChanged;
	public  $PaymentTime;
	public  $CustomerReferenceNo;
	public  $ProjectId;
	public  $OurReference;
	public  $IncludeVAT;
	public  $YourReference;
	public  $OrderTotalIncVat;
	public  $OrderTotalVat;
	public  $InvoiceTitle;
	public  $InvoiceText;
	public  $Paid;
	public  $OCR;
	public  $CustomerOrgNo;
	public  $Currency;
	public  $PaymentMethodId;
	public  $PaymentAmount;
	public  $ProductionManagerId;
	public  $SalesOpportunityId;
	public  $TypeOfSaleId;
	public  $Distributor;
	public  $DistributionMethod;
	public  $DepartmentId;
	public  $ExternalStatus;
	public  $InvoiceEmailAddress;
	/** @var Collection */
	private $InvoiceRows;
	public  $ProductionNumber;
	public  $DeliveryDate;
	public  $ReferenceInvoiceId;
	public  $ReferenceOrderId;
	public  $ReferenceNumber;
	public  $SkipStock;
	public  $AccrualDate;
	public  $AccrualLength;
	public  $RoundFactor;
	public  $InvoiceTemplateId;
	public  $VippsNumber;
	/** @var DeliveryMethod */
	public $DeliveryMethod;
	public $DeliveryAlternative;
	public $SendToFactoring;
	public $Commission;
	/** @var */
	public $UserDefinedDimensions;
	public $GLNNumber;
	public $CustomerDeliveryId;

	public function getInvoiceRows() {
		return collect( $this->InvoiceRows );
	}

	public function getAddresses() {
		return collect( $this->Addresses );
	}
}