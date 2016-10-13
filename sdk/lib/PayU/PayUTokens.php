<?php

/**
 * Manages all PayU tokens operations 
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 31/10/2013
 *
 */
class PayUTokens{
	
	/**
	 * Creates a credit card token
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function create($parameters, $lang = null){
	
		$required = array(PayUParameters::CREDIT_CARD_NUMBER,
						  PayUParameters::PAYER_NAME,
						  PayUParameters::PAYMENT_METHOD,
						  PayUParameters::PAYER_ID,
						  PayUParameters::CREDIT_CARD_EXPIRATION_DATE);
		
		CommonRequestUtil::validateParameters($parameters, $required);		
		
		$request = PayUTokensRequestUtil::buildCreateTokenRequest($parameters,$lang);
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API, RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	
	/**
	 * Finds a credit card token
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function find($parameters, $lang = null){
		
		$tokenId = CommonRequestUtil::getParameter($parameters, PayUParameters::TOKEN_ID);
		$required = null;
		
		if($tokenId == null){
			$required = array(PayUParameters::START_DATE, PayUParameters::END_DATE);
		}else{
			$required = array(PayUParameters::TOKEN_ID);
		}
	
		CommonRequestUtil::validateParameters($parameters, $required);
		
		$request = PayUTokensRequestUtil::buildGetCreditCardTokensRequest($parameters,$lang);
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
		
	}
	
	/**
	 * Removes a credit card token
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The transaction response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function remove($parameters, $lang=null){
		
		$required = array(PayUParameters::TOKEN_ID,
							PayUParameters::PAYER_ID);
		
		CommonRequestUtil::validateParameters($parameters, $required);
		
		$request = PayUTokensRequestUtil::buildRemoveTokenRequest($parameters,$lang);
		
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::PAYMENTS_API,RequestMethod::POST);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
		
	}
	
}