<?php

/**
 * Manages all PayU Subscription plans operations
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 22/12/2013
 *
 */
class PayUSubscriptionPlans{
	
	/**
	 * Creates a subscription plans
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function create($parameters, $lang = null){
		PayUSubscriptionsRequestUtil::validateSubscriptionPlan($parameters);
		
		$request = PayUSubscriptionsRequestUtil::buildSubscriptionPlan($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::PLAN_ENTITY, PayUSubscriptionsUrlResolver::ADD_OPERATION);
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);
		
		return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
	}
	
	/**
	 * Find a subscription plan by plan code
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function find($parameters, $lang = null){
		$required = array(PayUParameters::PLAN_CODE);
		CommonRequestUtil::validateParameters($parameters, $required);
		$plan = PayUSubscriptionsRequestUtil::buildSubscriptionPlan($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::PLAN_ENTITY,
				PayUSubscriptionsUrlResolver::GET_OPERATION,
				array($plan->planCode));
	
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest($plan, $payUHttpRequestInfo);
	}
	

	/**
	 * Updates a subscription plan
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function update($parameters, $lang = null){
		$required = array(PayUParameters::PLAN_CODE);
		$invalid = array(
				PayUParameters::PLAN_INTERVAL_COUNT,
				PayUParameters::ACCOUNT_ID, PayUParameters::PLAN_MAX_PAYMENTS,
				PayUParameters::PLAN_INTERVAL
		);
		
		CommonRequestUtil::validateParameters($parameters, $required, $invalid);
		
		$plan = PayUSubscriptionsRequestUtil::buildSubscriptionPlan($parameters);
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::PLAN_ENTITY,
				PayUSubscriptionsUrlResolver::EDIT_OPERATION,
				array($plan->planCode));
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::PUT);
		return PayUApiServiceUtil::sendRequest($plan, $payUHttpRequestInfo);
	}
	
	/**
	 * Deletes a subscription plan
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The response to the request sent
	 * @throws PayUException
	 * @throws InvalidArgumentException
	 */
	public static function delete($parameters, $lang = null){
		$required = array(PayUParameters::PLAN_CODE);
		CommonRequestUtil::validateParameters($parameters, $required);
		
		$plan = PayUSubscriptionsRequestUtil::buildSubscriptionPlan($parameters);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::PLAN_ENTITY,
											PayUSubscriptionsUrlResolver::DELETE_OPERATION,
											array($plan->planCode));
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::DELETE);
		return PayUApiServiceUtil::sendRequest($plan, $payUHttpRequestInfo);
	}
	
	
	
	/**
	 * Finds all subscription plans filtered by merchant or account
	 * by default the function filter by merchant if you need filter by account
	 * you can send in the parameters the account id
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class 
	 * @return the subscription plan list
	 * @throws PayUException
	 * @throws InvalidParametersException
	 * @throws InvalidArgumentException
	 */
	public static function listPlans($parameters, $lang = null){
		
		$request = new stdClass();
		$request->accountId = CommonRequestUtil::getParameter($parameters, PayUParameters::ACCOUNT_ID);
		$request->limit = CommonRequestUtil::getParameter($parameters, PayUParameters::LIMIT);
		$request->offset = CommonRequestUtil::getParameter($parameters, PayUParameters::OFFSET);
		
		$urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(PayUSubscriptionsUrlResolver::PLAN_ENTITY,
				PayUSubscriptionsUrlResolver::QUERY_OPERATION);
		
		$urlSegment = CommonRequestUtil::addQueryParamsToUrl($urlSegment, $request);		
		
		$payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
		return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
	}
	
	
	
}