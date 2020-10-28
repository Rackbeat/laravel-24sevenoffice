<?php


namespace KgBot\SO24\Services;


use Illuminate\Support\Collection;
use KgBot\SO24\Exceptions\InvoiceTransferException;

class InvoiceService extends BaseService
{
	public function setUp(): string {
		return 'http://api.24sevenoffice.com/Economy/InvoiceOrder/V001/InvoiceService.asmx?WSDL';
	}

	protected function getIndexMethod(): string {
		return 'GetInvoices';
	}

	protected function getIndexReturnName() {
		return [ 'invoiceReturnProperties', 'rowReturnProperties' ];
	}

	protected function getIndexSearchName() {
		return 'searchParams';
	}

	protected function getRowReturnPropertiesReturnQuery() {
		return [
			'ProductId',
			'ProductNo',
			'RowId',
			'VatRate',
			'Price',
			'Name',
			'DiscountRate',
			'Quantity',
			'QuantityDelivered',
			'QuantityOrdered',
			'QuantityRest',
			'Cost',
			'InPrice',
			'Type',
			'SequenceNumber',
		];
	}

	protected function getInvoiceReturnPropertiesReturnQuery() {
		return [
			'OrderId',
			'CustomerId',
			'Addresses',
			'OrderStatus',
			'InvoiceId',
			'DateOrdered',
			'DateInvoiced',
			'PaymentTime',
			'OurReference',
			'ReferenceInvoiceId',
			'ReferenceOrderId',
			'ReferenceNumber',
			'OrderTotalIncVat',
			'OrderTotalVat',
			'PaymentAmount',
			'InvoiceEmailAddress',
			'InvoiceRows',
			'DeliveryDate',
			'Currency',
			'DeliveryMethod',
			'YourReference',
			'InvoiceTitle',
			'InvoiceText',
		];
	}

	public function find( $id, array $request = [] ) {
		if ( isset( $request['searchParams'] ) ) {
			$request['searchParams']['OrderIds'] = [ $id ];
		} else {
			$request['searchParams'] = [
				'OrderIds' => [ $id ]
			];
		}

		$response = $this->get( $request );

		if ( is_countable( $response ) ) {
			if ( $response instanceof Collection ) {
				return $response->first();
			}
			if ( isset( $response[0] ) ) {
				return $response[0];
			}
		}

		return $response;
	}

	/**
	 * @param array $data
	 *
	 * @return mixed
	 * @throws InvoiceTransferException
	 * @throws \SoapFault
	 */
	public function createOrUpdate( $data = [] ) {
		$response = $this->request->call( 'SaveInvoices', [
			'invoices' => [ 'InvoiceOrder' => $data ]
		] );

		if ( ! isset( $response->SaveInvoicesResult ) ) {
			throw new InvoiceTransferException( json_encode( $response ) );
		}

		$response = $response->SaveInvoicesResult->InvoiceOrder;

		if ( isset( $response->APIException ) ) {
			throw new InvoiceTransferException( '24SO API Exception: ' . $response->APIException->Message, 500 );
		}

		return (object) $response;
	}

	/**
	 * @param array $request
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function delivery_methods( $request = [] ): array {
		$response = $this->request->call( 'GetDeliveryMethods', $request )->GetDeliveryMethodsResult->DeliveryMethod ?? [];

		return is_array( $response ) ? $response : [ $response ];
	}

	/**
	 * @param array $request
	 *
	 * @return array
	 * @throws \SoapFault
	 */
	public function payment_methods( $request = [] ): array {
		$response = $this->request->call( 'GetPaymentMethods', $request )->GetPaymentMethodsResult->PaymentMethod ?? [];

		return is_array( $response ) ? $response : [ $response ];
	}
}
