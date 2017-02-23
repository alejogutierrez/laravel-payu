<?php

/**
 * Manages all PayU payments operations
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 * 
 */
class PayUPayments{
	
	/** Constant to CODENSA Payment method */
	const PAYMENT_METHOD_CODENSA = 'CODENSA';	
	
	/**
	 * Makes a ping request
	 * @param string $lang language of request see SupportedLanguages class
	 * @throws PayUException 
	 * @return The response to the ping request sent
	 */ 	
	static function doPing($lang = null){
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest(RequestPaymentsUtil::buildPingRequest($lang), $payUHttpRequestInfo);
	}
	
	
	/**
	 * Makes a get payment methods request
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The payment method list
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function getPaymentMethods($lang = null){
		$request = RequestPaymentsUtil::buildPaymentMethodsListRequest($lang);
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Evaluate if a payment method is available in Payments API
	 * @param string $paymentMethodParameter the payment method to evaluate
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The payment method information 
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function getPaymentMethodAvailability($paymentMethodParameter, $lang = null){
		$request = RequestPaymentsUtil::buildPaymentMethodAvailabilityRequest($paymentMethodParameter, $lang);
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * list PSE Banks 
	 *
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * 
	 * @return The bank list information
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 * 
	 */
	public static function getPSEBanks($parameters, $lang = null){
		CommonRequestUtil::validateParameters($parameters, array(PayUParameters::COUNTRY));
		$paymentCountry = CommonRequestUtil::getParameter($parameters, PayUParameters::COUNTRY);
		$request = RequestPaymentsUtil::buildBankListRequest($paymentCountry);
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	
	/**
	 * Do an authorization and capture transaction 
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 * 
	 */
	public static function doAuthorizationAndCapture($parameters, $lang = null)
	{
		return PayUPayments::doPayment($parameters,TransactionType::AUTHORIZATION_AND_CAPTURE, $lang);
	}
	
	

	/**
	 * Makes payment petition
	 *
	 * @param parameters The parameters to be sent to the server
	 * @param transactionType
	 *            The type of the payment transaction
	 * @param string $lang language of request see SupportedLanguages class            
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */	
	public static function doPayment($parameters, $transactionType, $lang){
		
		$requiredAll = array(
				PayUParameters::REFERENCE_CODE,
				PayUParameters::DESCRIPTION,
				PayUParameters::CURRENCY,
				PayUParameters::VALUE,
		);
		
		$paymentMethodParameter = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYMENT_METHOD);
		
		if($paymentMethodParameter != null){
			
			$responseAvailability = PayUPayments::getPaymentMethodAvailability($paymentMethodParameter, $lang);
			$paymentMethod = $responseAvailability->paymentMethod;
			
			if (array_key_exists(PayUParameters::TOKEN_ID,$parameters)) {
				
				$requiredTokenId = array(
				PayUParameters::INSTALLMENTS_NUMBER,
				PayUParameters::TOKEN_ID);
				
				$required = array_merge($requiredAll, $requiredTokenId);
			
			}else if( array_key_exists(PayUParameters::CREDIT_CARD_NUMBER,$parameters) ){
				
				$requiredCreditCard = array(
						PayUParameters::INSTALLMENTS_NUMBER,
						PayUParameters::CREDIT_CARD_NUMBER,
						PayUParameters::PAYER_NAME,
						PayUParameters::CREDIT_CARD_EXPIRATION_DATE,
						PayUParameters::PAYMENT_METHOD);
					
				
				$processWithoutCvv2 = PayUPayments::isProcessWithoutCvv2Param($parameters);
				if(!$processWithoutCvv2){
					$requiredCreditCard[] = PayUParameters::CREDIT_CARD_SECURITY_CODE;
				}
				
				if(PayUPayments::PAYMENT_METHOD_CODENSA == $paymentMethodParameter){
					$requiredCreditCard[] = PayUParameters::PAYER_DNI;
					$requiredCreditCard[] = PayUParameters::PAYER_DNI_TYPE;
				}
												
				$required = array_merge($requiredAll, $requiredCreditCard);
					
			}else if($paymentMethod != null && (PayUPaymentMethodType::CASH == $paymentMethod->type )){
				$requiredCash = array(
						PayUParameters::PAYER_NAME,
						PayUParameters::PAYER_DNI,
						PayUParameters::PAYMENT_METHOD);
				
				$required = array_merge($requiredAll, $requiredCash);
				
			}else if("BOLETO_BANCARIO" == $paymentMethodParameter) {
				$requiredBoletoBancario = array(PayUParameters::PAYER_NAME,
						PayUParameters::PAYER_DNI,
						PayUParameters::PAYMENT_METHOD,
						PayUParameters::PAYER_STREET,
						PayUParameters::PAYER_STREET_2,
						PayUParameters::PAYER_CITY,
						PayUParameters::PAYER_STATE,
						PayUParameters::PAYER_POSTAL_CODE
				);
				
				$required = array_merge($requiredAll, $requiredBoletoBancario);
			}
			else if("PSE" == $paymentMethodParameter) {
				$requiredPSE = array(
						PayUParameters::REFERENCE_CODE,
						PayUParameters::DESCRIPTION,
						PayUParameters::CURRENCY,
						PayUParameters::VALUE,
						PayUParameters::PAYMENT_METHOD,
						PayUParameters::PAYER_NAME,
						PayUParameters::PAYER_DOCUMENT_TYPE,
						PayUParameters::PAYER_DNI,
						PayUParameters::PAYER_EMAIL,
						PayUParameters::PAYER_CONTACT_PHONE,
						PayUParameters::PSE_FINANCIAL_INSTITUTION_CODE,
						PayUParameters::PAYER_PERSON_TYPE,
						PayUParameters::IP_ADDRESS,
						PayUParameters::PAYER_COOKIE,
						PayUParameters::USER_AGENT);			
				$required = array_merge($requiredAll, $requiredPSE);
			
			}
			else if ($paymentMethod != null && ($paymentMethod->type == PayUPaymentMethodType::CREDIT_CARD)) {
				throw new InvalidArgumentException ( "Payment method credit card require at least one of two parameters [" 
						. PayUParameters::CREDIT_CARD_NUMBER . '] or [' . PayUParameters::TOKEN_ID . ']' );
			}else{
				$required = $requiredAll;
			}
		} else {
			throw new InvalidArgumentException ( sprintf ( "The payment method value is invalid" ) );
		}
		
		CommonRequestUtil::validateParameters($parameters, $required);
		$request = RequestPaymentsUtil::buildPaymentRequest($parameters, $transactionType, $lang);
		
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Process a transaction already authorizated
	 *
	 * @param parameters The parameters to be sent to the server
	 * @param transactionType
	 *            The type of the payment transaction
	 * @param string $lang language of request see SupportedLanguages class            
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */	
	private static function processTransactionAlreadyAuthorizated($parameters, $transactionType, $lang){
		$required = array(PayUParameters::TRANSACTION_ID,
						PayUParameters::ORDER_ID);
		
		CommonRequestUtil::validateParameters($parameters, $required);
		$request = RequestPaymentsUtil::buildPaymentRequest($parameters, $transactionType, $lang);
		
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Do an authorization transaction
	 *
	 * @param parameters to build the request
	 * @param string $lang language of request see SupportedLanguages class 
	 * @return The request response
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function  doAuthorization($parameters, $lang = null){
		return PayUPayments::doPayment($parameters, TransactionType::AUTHORIZATION, $lang);
	}
	
	
	/**
	 * Do a capture transaction
	 *
	 * @param parameters to build the request
	 * @param string $lang language of request see SupportedLanguages class 
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function doCapture($parameters, $lang = NULL){
		return PayUPayments::processTransactionAlreadyAuthorizated($parameters, TransactionType::CAPTURE, $lang);
	}
	
	/**
	 * Do a void (Cancel) transaction
	 *
	 * @param parameters to build the request
	 * @param string $lang language of request see SupportedLanguages class 
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function doVoid($parameters, $lang = NULL){
		return PayUPayments::processTransactionAlreadyAuthorizated($parameters, TransactionType::VOID, $lang);
	}
	
	/**
	 * Do a refund transaction
	 *
	 * @param parameters to build the request
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function doRefund($parameters, $lang = NULL){
		return PayUPayments::processTransactionAlreadyAuthorizated($parameters, TransactionType::REFUND, $lang);
	}
	
	/**
	 * Get the value for parameter processWithoutCvv2 if the parameter doesn't exist
	 * in the parameters array or the parameter value isn't valid boolean representation return false
	 * the otherwise return the parameter value
	 * @param array $parameters
	 * @return boolean whith the value for processWithoutCvv2 parameter, if the parameter doesn't exist in the array or 
	 * it has a invalid boolean value returs false;
	 */
	private static function isProcessWithoutCvv2Param($parameters){
		$processWithoutCvv2 = 
		CommonRequestUtil::getParameter($parameters, PayUParameters::PROCESS_WITHOUT_CVV2);
		
		if(is_bool($processWithoutCvv2)){
			return $processWithoutCvv2;
		}else{
			return false;
		}
	}
	
}