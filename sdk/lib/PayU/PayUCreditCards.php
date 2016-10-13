<?php

/**
 * Manages all PayU credit card operations
 * over subscriptions
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 22/12/2013
 *
 */
class PayUCreditCards{

	/**
	 * Creates a credit card 
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function create($parameters, $lang = null){

		PayUSubscriptionsRequestUtil::validateCreditCard($parameters);
		
		$customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		if( !isset($customerId) ){
			throw new InvalidArgumentException(" The parameter customer id is mandatory ");
		}
		

		$request = PayUSubscriptionsRequestUtil::buildCreditCard($parameters);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CREDIT_CARD_ENTITY, 
				PayUSubscriptionsUrlResolver::ADD_OPERATION, 
				array($parameters[PayUParameters::CUSTOMER_ID]));

		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);

		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * finds a credit card
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function find($parameters, $lang = null){
	
		$required = array(PayUParameters::TOKEN_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		$creditCard = PayUSubscriptionsRequestUtil::buildCreditCard($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CREDIT_CARD_ENTITY,
				PayUSubscriptionsUrlResolver::GET_OPERATION,
				array($creditCard->token));
	
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest($creditCard, $payUHttpRequestInfo);
	}
	
	/**
	 * Returns the credit card list with the query params
	 *
	 * @param parameters
	 *            The parameters to be sent to the server
	 * @return the credit card list
	 * @throws PayUException
	 * @throws InvalidParametersException
	 * @throws ConnectionException
	 */
	public static function findList($parameters, $lang = null)	{	
		
		$required = array(PayUParameters::CUSTOMER_ID);
		CommonRequestUtil::validateParameters($parameters, $required);

		$request = new stdClass();
		$request->customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		$creditCard = PayUSubscriptionsRequestUtil::buildCreditCard($parameters);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CREDIT_CARD_ENTITY,
				PayUSubscriptionsUrlResolver::GET_LIST_OPERATION,
				array($creditCard->customerId));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest($creditCard, $payUHttpRequestInfo);
	}

	/**
	 * Updates a credit card
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function update($parameters, $lang = null){
	
		$required = array(PayUParameters::TOKEN_ID);
		$invalid = array(PayUParameters::CUSTOMER_ID, 
						 PayUParameters::CREDIT_CARD_NUMBER,
						 PayUParameters::PAYMENT_METHOD);
		
		CommonRequestUtil::validateParameters($parameters, $required,  $invalid);
		$creditCard = PayUSubscriptionsRequestUtil::buildCreditCard($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CREDIT_CARD_ENTITY,
				PayUSubscriptionsUrlResolver::EDIT_OPERATION,
				array($creditCard->token));
	
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::PUT);
		return PayUApiServiceUtil::sendRequest($creditCard, $payUHttpRequestInfo);
		
	}

	/**
	 * Deletes a credit card
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function delete($parameters, $lang = null){
	
		$required = array(PayUParameters::TOKEN_ID, PayUParameters::CUSTOMER_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		
		$creditCard = PayUSubscriptionsRequestUtil::buildCreditCard($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CREDIT_CARD_ENTITY,
				PayUSubscriptionsUrlResolver::DELETE_OPERATION,
				array($creditCard->customerId, $creditCard->token));
	
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::DELETE);

		return PayUApiServiceUtil::sendRequest($creditCard, $payUHttpRequestInfo);
	}
	
	/**
	 * Returns all parameter names of Credit Card
	 * @return list of parameter names
	 */
	public static function getParameterNames(){
	
		$parameterNames = array(PayUParameters::TOKEN_ID,
		PayUParameters::CREDIT_CARD_NUMBER,
		PayUParameters::CREDIT_CARD_EXPIRATION_DATE, 
		PayUParameters::PAYMENT_METHOD,
		PayUParameters::PAYER_NAME, 
		PayUParameters::PAYER_STREET,
		PayUParameters::PAYER_STREET_2, 
		PayUParameters::PAYER_STREET_3,
		PayUParameters::PAYER_CITY, 
		PayUParameters::PAYER_STATE,
		PayUParameters::PAYER_COUNTRY, 
		PayUParameters::PAYER_POSTAL_CODE,
		PayUParameters::PAYER_PHONE);
		return $parameterNames;
	}
	
	/**
	 * Indicates whether any of the parameters of CRedit Card is within the parameters list
	 * @param parameters The parametrs to evaluate
	 * @return <boolean> returns true if the parameter is in the set
	 */
	public static function existParametersCreditCard($parameters){
		$keyNamesSet = self::getParameterNames();
		return CommonRequestUtil::isParameterInSet($parameters, $keyNamesSet);
	}
}