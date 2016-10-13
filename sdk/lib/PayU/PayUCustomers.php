<?php

/**
 * Manages all PayU customers  operations 
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 22/12/2013
 *
 */
class PayUCustomers{
	
	/**
	 * Creates a customer 
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function create($parameters, $lang = null){
	
		PayUSubscriptionsRequestUtil::validateCustomer($parameters);
		
		$request = PayUSubscriptionsRequestUtil::buildCustomer($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CUSTOMER_ENTITY, PayUSubscriptionsUrlResolver::ADD_OPERATION);
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);
		
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Creates a customer with bank account information
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function createCustomerWithBankAccount($parameters, $lang = null){
	
		PayUSubscriptionsRequestUtil::validateCustomer($parameters);
	
		$customer = PayUSubscriptionsRequestUtil::buildCustomer($parameters);
		$bankAccount = RequestPaymentsUtil::buildBankAccountRequest($parameters);

		$customer->bankAccounts = array($bankAccount);
	
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CUSTOMER_ENTITY, PayUSubscriptionsUrlResolver::ADD_OPERATION);
	
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);
	
		return PayUApiServiceUtil::sendRequest($customer, $payUHttpRequestInfo);
	}
	
	
	/**
	 * Creates a customer with credit card information
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function createCustomerWithCreditCard($parameters, $lang = null){
		
		PayUSubscriptionsRequestUtil::validateCustomer($parameters);
		PayUSubscriptionsRequestUtil::validateCreditCard($parameters);
		
		$customer = PayUSubscriptionsRequestUtil::buildCustomer($parameters);
		$creditCard = PayUSubscriptionsRequestUtil::buildCreditCard($parameters);

		$creditCards =  array($creditCard);
		$customer->creditCards = $creditCards;
		
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CUSTOMER_ENTITY, PayUSubscriptionsUrlResolver::ADD_OPERATION);
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);
		
		return PayUApiServiceUtil::sendRequest($customer, $payUHttpRequestInfo);
	}
	
	/**
	 * Finds a customer by id
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function find($parameters, $lang = null){
		
		$required = array(PayUParameters::CUSTOMER_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		$customer = PayUSubscriptionsRequestUtil::buildCustomer($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CUSTOMER_ENTITY, 
																				PayUSubscriptionsUrlResolver::GET_OPERATION,
																				array($customer->id));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest($customer, $payUHttpRequestInfo);
	}
	

	/**
	 * Updates a customer
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function update($parameters, $lang = null){
		$required = array(PayUParameters::CUSTOMER_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		
		PayUSubscriptionsRequestUtil::validateCustomer($parameters);
		$customer = PayUSubscriptionsRequestUtil::buildCustomer($parameters);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CUSTOMER_ENTITY,
																				PayUSubscriptionsUrlResolver::EDIT_OPERATION,
																				array($customer->id));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::PUT);
		
		return PayUApiServiceUtil::sendRequest($customer, $payUHttpRequestInfo);
	}
	
	/**
	 * Deletes a customer
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function delete($parameters, $lang = null){
		$required = array(PayUParameters::CUSTOMER_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		
		$customer = PayUSubscriptionsRequestUtil::buildCustomer($parameters);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CUSTOMER_ENTITY,
				PayUSubscriptionsUrlResolver::DELETE_OPERATION,
				array($customer->id));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::DELETE);
		
		return PayUApiServiceUtil::sendRequest($customer, $payUHttpRequestInfo);
	}
	
	/**
	 * Finds the customers associated to a plan by plan id or by plan code
	 * 
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * 
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function findCustomerListByPlanIdOrPlanCode($parameters, $lang = null){
		$request = new stdClass();
		$request->planId = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_ID);
		$request->planCode = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_CODE);
		$request->limit = CommonRequestUtil::getParameter($parameters, PayUParameters::LIMIT);
		$request->offset = CommonRequestUtil::getParameter($parameters, PayUParameters::OFFSET);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::CUSTOMER_ENTITY,
				PayUSubscriptionsUrlResolver::CUSTOMERS_PARAM_SEARCH,
				null);
		
		$urlSegment = CommonRequestUtil::addQueryParamsToUrl($urlSegment, $request);
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
		
	}	
	
}