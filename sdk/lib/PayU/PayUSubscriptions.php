<?php

/**
 * Manages all PayU subscriptions operations 
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/12/2013
 *
 */
class PayUSubscriptions{
	
	/**
	 * Creates a subscription
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function createSubscription($parameters, $lang = null){
	
		$planCode = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_CODE);
		$tokenId = CommonRequestUtil::getParameter($parameters, PayUParameters::TOKEN_ID);
		if(!isset($planCode)){
			PayUSubscriptionsRequestUtil::validateSubscriptionPlan($parameters);
		}
		
		PayUSubscriptionsRequestUtil::validateCustomerToSubscription($parameters);
		
		$existParamBankAccount = PayUBankAccounts::existParametersBankAccount($parameters);
		$existParamCreditCard = PayUCreditCards::existParametersCreditCard($parameters);
		
		self::validatePaymentMethod($parameters, $existParamBankAccount, $existParamCreditCard);
		
 		$request = PayUSubscriptionsRequestUtil::buildSubscription($parameters, $existParamBankAccount, $existParamCreditCard);
 		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::SUBSCRIPTIONS_ENTITY, PayUSubscriptionsUrlResolver::ADD_OPERATION);
		
 		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);
		
 		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	
	/**
	 * Update a subscription
	 * @param parameters The parameters to be sent to the server
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function update($parameters, $lang = null) {

		$required = array(PayUParameters::SUBSCRIPTION_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		$subscriptionId = CommonRequestUtil::getParameter($parameters, PayUParameters::SUBSCRIPTION_ID);

		//validates in edition mode
		PayUSubscriptionsRequestUtil::validateCustomerToSubscription($parameters, TRUE);
		
		$existParamBankAccount = PayUBankAccounts::existParametersBankAccount($parameters);
		$existParamCreditCard = PayUCreditCards::existParametersCreditCard($parameters);
		
		//Validate in edition mode
		self::validatePaymentMethod($parameters, $existParamBankAccount, $existParamCreditCard, TRUE);
		
		//Build request in edition mode
		$request = PayUSubscriptionsRequestUtil::buildSubscription($parameters, $existParamBankAccount, $existParamCreditCard, TRUE);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::SUBSCRIPTIONS_ENTITY, 
				PayUSubscriptionsUrlResolver::EDIT_OPERATION, array($subscriptionId));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::PUT);
		
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	
	/**
	 * Cancels a subscription
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function cancel($parameters, $lang = null){
		$required = array(PayUParameters::SUBSCRIPTION_ID);
		CommonRequestUtil::validateParameters($parameters, $required);

		$request = PayUSubscriptionsRequestUtil::buildSubscription($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::SUBSCRIPTIONS_ENTITY,
																				PayUSubscriptionsUrlResolver::DELETE_OPERATION,
																				array($parameters[PayUParameters::SUBSCRIPTION_ID]));
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::DELETE);
		
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
		
	}
	
	
	/**
	 * Find the subscription with the given id
	 *
	 * @param parameters The parameters to be sent to the server
	 * @return the finded Subscription
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function find($parameters, $lang = null){
	
		$required = array(PayUParameters::SUBSCRIPTION_ID);
		CommonRequestUtil::validateParameters($parameters, $required);
		$subscriptionId = CommonRequestUtil::getParameter($parameters, PayUParameters::SUBSCRIPTION_ID);
	
		$request = PayUSubscriptionsRequestUtil::buildSubscription($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::SUBSCRIPTIONS_ENTITY,
				PayUSubscriptionsUrlResolver::GET_OPERATION, array($subscriptionId));
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}	
	
	/**
	 * Finds the subscriptions associated to a customer by either
	 * payer id, plan id, plan code, accoun id and account status
	 * using an offset and a limit 
	 *
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 *
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function findSubscriptionsByPlanOrCustomerOrAccount($parameters, $lang = null){
		$request = new stdClass();
		$request->planId = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_ID);
		$request->planCode = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_CODE);
		$request->state = CommonRequestUtil::getParameter($parameters, PayUParameters::ACCOUNT_STATE);
		$request->customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		$request->accountId = CommonRequestUtil::getParameter($parameters, PayUParameters::ACCOUNT_ID);
		$request->limit = CommonRequestUtil::getParameter($parameters, PayUParameters::LIMIT);
		$request->offset = CommonRequestUtil::getParameter($parameters, PayUParameters::OFFSET);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance ()->getUrlSegment ( PayUSubscriptionsUrlResolver::SUBSCRIPTIONS_ENTITY, UrlResolver::GET_LIST_OPERATION, null );
	
		$urlSegment = CommonRequestUtil::addQueryParamsToUrl($urlSegment, $request);
	
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
	
	}
	
	/**
	 * validate the Payment Method parameters. Only one payment methos is permitted
	 * @param $parameters
	 * @param $existParamBankAccount
	 * @param $existParamCreditCard
	 * @throws PayUException
	 */
	public static function validatePaymentMethod($parameters, $existParamBankAccount, $existParamCreditCard, $edit = FALSE) {
		if ($existParamBankAccount == TRUE && $existParamCreditCard == TRUE){
			throw new PayUException(PayUErrorCodes::INVALID_PARAMETERS, "The subscription must have only one payment method");
		}else if ($existParamBankAccount == TRUE){
			PayUSubscriptionsRequestUtil::validateBankAccount($parameters);
			if ($edit == FALSE){
				//The TERMS_AND_CONDITIONS_ACEPTED Parameter is required for Bank Account
				$required = array(PayUParameters::TERMS_AND_CONDITIONS_ACEPTED);
				CommonRequestUtil::validateParameters($parameters, $required);
			}
		}
		else if ($existParamCreditCard == TRUE){
			PayUSubscriptionsRequestUtil::validateCreditCard($parameters);
		}else{
			throw new PayUException(PayUErrorCodes::INVALID_PARAMETERS, "The subscription must have one payment method");
		}
	}
	
}