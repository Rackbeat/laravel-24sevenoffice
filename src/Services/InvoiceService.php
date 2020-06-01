<?php


namespace KgBot\SO24\Services;


use App\Exceptions\InvoiceTransferException;

class InvoiceService extends BaseService
{
	public function setUp(): string {
		return 'http://api.24sevenoffice.com/Economy/InvoiceOrder/V001/InvoiceService.asmx?WSDL';
	}

	/**
	 * @param array $query
	 *
	 * @return array|mixed
	 * @throws \SoapFault
	 */
	public function get( $query = [] ) {
		$response = (array) $this->request->call( 'GetInvoices', $query )->GetInvoicesResult;

		return ( isset( $response['InvoiceOrder'] ) && is_array( $response['InvoiceOrder'] ) ) ? $response['InvoiceOrder'] : $response;
	}

	public function find( $id, array $request = [] ) {
		if ( isset( $request['searchParams'] ) ) {
			$request['searchParams']['OrderIds'] = [ $id ];
		} else {
			$request['searchParams'] = [
				'OrderIds' => [ $id ]
			];
		}

		if ( ! isset( $request['invoiceReturnProperties'] ) ) {
			$request['invoiceReturnProperties'] = [
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
				'OrderTotalIncVat',
				'OrderTotalVat',
				'PaymentAmount',
				'InvoiceEmailAddress',
				'InvoiceRows',
				'DeliveryDate',
				'Currency',
				'DeliveryMethod',
				'YourReference',
			];
		}

		if ( ! isset( $request['rowReturnProperties'] ) ) {
			$request['rowReturnProperties'] = [
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
			];
		}

		$response = (array) $this->get( $request );

		return $response['InvoiceOrder'] ?? $response;
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
