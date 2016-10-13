<?php

/**
 * Manages all PayU Bank Accounts operations over payment plans
 *
 * @author PayU Latam
 * @version 1.0.0, 16/09/2014
 *
 */
class PayUBankAccounts{
	
	
	/**
	 * Creates a bank account to payments
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function create($parameters, $lang = null){

		$customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		if( !isset($customerId) ){
			throw new InvalidArgumentException(" The parameter customer id is mandatory ");
		}
		
		$request = RequestPaymentsUtil::buildBankAccountRequest($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance ()->getUrlSegment ( PayUSubscriptionsUrlResolver::BANK_ACCOUNT_ENTITY, PayUSubscriptionsUrlResolver::ADD_OPERATION, array (
				$parameters [PayUParameters::CUSTOMER_ID] 
		));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);
	
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Deletes a bank account
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function delete($parameters, $lang = null){
		
		$required = array(PayUParameters::CUSTOMER_ID, PayUParameters::BANK_ACCOUNT_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
	
		$customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		$bankAccountId = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_ID);
	
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::BANK_ACCOUNT_ENTITY,
				PayUSubscriptionsUrlResolver::DELETE_OPERATION,
				array($customerId, $bankAccountId));
	
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::DELETE);
	
		return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
	}
	
	/**
	 * Updates a bank account
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function update($parameters, $lang = null){
		
		$required = array(PayUParameters::BANK_ACCOUNT_ID);
		CommonRequestUtil::validateParameters($parameters, $required);

		$request = RequestPaymentsUtil::buildBankAccountRequest($parameters);

		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::BANK_ACCOUNT_ENTITY,
				PayUSubscriptionsUrlResolver::EDIT_OPERATION,
				array($request->id));

		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::PUT);

		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Return a bank account with the given id
	 *
	 * @param parameters The parameters to be sent to the server
	 * @return the find bank account
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function find($parameters, $lang = null){
	
		$required = array(PayUParameters::BANK_ACCOUNT_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		
		$bankAccountRequest = RequestPaymentsUtil::buildBankAccountRequest($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::BANK_ACCOUNT_ENTITY,
				PayUSubscriptionsUrlResolver::GET_OPERATION,
				array($bankAccountRequest->id));
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest($bankAccountRequest, $payUHttpRequestInfo);
	}	
	
	/**
	 * Finds the bank accounts associated to a customer by customer id
	 * 
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * 
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function findListByCustomer($parameters, $lang = null){
		$request = new stdClass();
		$request->customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::BANK_ACCOUNT_ENTITY,
				PayUSubscriptionsUrlResolver::GET_LIST_OPERATION, array($request->customerId));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Returns all parameter names of Bank Account
	 * @return list of parameter names
	 */
	public static function getParameterNames(){
	
		$parameterNames = array(PayUParameters::BANK_ACCOUNT_ID,
				PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER,
				PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER_TYPE,
				PayUParameters::BANK_ACCOUNT_CUSTOMER_NAME,
				PayUParameters::BANK_ACCOUNT_AGENCY_NUMBER,
				PayUParameters::BANK_ACCOUNT_AGENCY_DIGIT,
				PayUParameters::BANK_ACCOUNT_ACCOUNT_DIGIT,
				PayUParameters::BANK_ACCOUNT_NUMBER,
				PayUParameters::BANK_ACCOUNT_BANK_NAME,
				PayUParameters::BANK_ACCOUNT_TYPE,
				PayUParameters::BANK_ACCOUNT_STATE );
		return $parameterNames;
	}	
	
	/**
	 * Indicates whether any of the parameters for Bank Account is within the parameters list
	 * @param parameters The parametrs to evaluate
	 * @return <boolean> returns true if the parameter is in the set
	 */
	public static function existParametersBankAccount($parameters){
		$keyNamesSet = self::getParameterNames();
		return CommonRequestUtil::isParameterInSet($parameters, $keyNamesSet);
	}
	
	
}