<?php

/**
 * Contains information about the Environment setup
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */
class Environment {
	
	/** name for payments api*/
	const PAYMENTS_API = "PAYMENTS_API";
	
	/** name for reports api*/
	const REPORTS_API = "REPORTS_API";
	
	/** name for subscriptions api */
	const SUBSCRIPTIONS_API = "SUBSCRIPTIONS_API";
	
	/** url used to payments service api  */
	private static $paymentsUrl = "https://api.payulatam.com/payments-api/4.0/service.cgi";
	
	/** url used to reports service api  */
	private static $reportsUrl = "https://api.payulatam.com/reports-api/4.0/service.cgi";
	
	/** url used to subscriptions service api  */
	private static $subscriptionsUrl = "https://api.payulatam.com/payments-api/rest/v4.3";
	
	/** url used to subscriptions service api  if the test variable is true */
	private static $paymentsTestUrl = "https://api.payulatam.com/payments-api/4.0/service.cgi";
	
	/** url used to reports service api  if the test variable is true */
	private static $reportsTestUrl = "https://api.payulatam.com/reports-api/4.0/service.cgi";
	
	/** url used to subscriptions service api  if the test variable is true */
	private static $subscriptionsTestUrl = "https://api.payulatam.com/payments-api/rest/v4.3";
	
	/** url used to subscriptions service api  if is not null*/
	private static $paymentsCustomUrl = null;

	/** url used to reports service api  if is not null*/
	private static $reportsCustomUrl = null;
	
	/** url used to subscriptions service api  if is not null*/
	private static $subscriptionsCustomUrl = null;
	
	
	/** if this is true the test url is used to request*/
	static $test = false;
	
	
	/**
	 * Gets the suitable url to the api sent
	 * @param  the api to get the url it can have three values 
	 * PAYMENTS_API, REPORTS_API, SUBSCRIPTIONS_API
	 * @throws InvalidArgumentException if the api value doesn't have a valid value
	 * @return string with the url  
	 */
	static function getApiUrl($api){
		switch ($api){
			case Environment::PAYMENTS_API:
				return Environment::getPaymentsUrl();
			case Environment::REPORTS_API:
				return Environment::getReportsUrl();
			case Environment::SUBSCRIPTIONS_API:
				return Environment::getSubscriptionUrl();
			default:
				throw new InvalidArgumentException(sprintf('the api argument [%s] is invalid please check the Environment class ' ,$api));
		}
	}
	
	/**
	 * Returns the payments url
	 * @return  the paymets url configured
	 */
	static function getPaymentsUrl() {
		if(isset(Environment::$paymentsCustomUrl)) {
			return Environment::$paymentsCustomUrl;
		}
		
		if(!Environment::$test) {
			return Environment::$paymentsUrl;
		}else {
			return Environment::$paymentsTestUrl;
		}
	}
	
	/**
	 * Returns the reports url
	 * @return the reports url
	 */
	static function getReportsUrl() {
		if(Environment::$reportsCustomUrl != null) {
			return Environment::$reportsCustomUrl;
		}
		
		if(!Environment::$test) {
			return Environment::$reportsUrl;
		}else {
			return Environment::$reportsTestUrl;
		}
	}
	
	/**
	 * Returns the subscriptions url
	 * @return the subscriptions url
	 */
	static function getSubscriptionUrl() {
		if(Environment::$subscriptionsCustomUrl != null) {
			return Environment::$subscriptionsCustomUrl;
		}
	
		if(!Environment::$test) {
			return Environment::$subscriptionsTestUrl;
		}else {
			return Environment::$subscriptionsUrl;
		}
	}
	
	
	/**
	 * Set a  custom payments url
	 * @param string $paymentsCustomUrl
	 */
	static function setPaymentsCustomUrl($paymentsCustomUrl) {
		Environment::$paymentsCustomUrl = $paymentsCustomUrl;
	}

	/**
	 * Set a custom reports url
	 * @param string $reportsCustomUrl
	 */
	static function setReportsCustomUrl($reportsCustomUrl) {
		Environment::$reportsCustomUrl = $reportsCustomUrl;
	}

	/**
	 * Set a custom subscriptions url
	 * @param string $subscriptionsCustomUrl
	 */
	static function setSubscriptionsCustomUrl($subscriptionsCustomUrl){
		Environment::$subscriptionsCustomUrl = $subscriptionsCustomUrl;
	}
	
	/**
	 * Validates the Environment before process any request
	 * @throws ErrorException
	 */
	static function validate() {
		if(version_compare(PHP_VERSION, '5.2.1', '<'))  {
			throw new ErrorException('PHP version >= 5.2.1 required');
		}
		
		
		$requiredExtensions = array('curl','xml','mbstring','json');
		foreach ($requiredExtensions AS $ext)  {
			if (!extension_loaded($ext))  {
				throw new ErrorException('The Payu library requires the ' . $ext . ' extension.');
			}
		}
	}
	
}