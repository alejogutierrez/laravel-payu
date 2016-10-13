<?php

/**
 *
 * Utility class to process parameters and send requests
 * over subscription service
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/12/2013
 *
 */

class PayUSubscriptionsRequestUtil extends CommonRequestUtil{
	
	/**
	 * Build a subscription request
	 * @param array $parameters
	 * @return stdClass with the subscriptionrequest built
	 */
	public static function buildSubscription($parameters, $existParamBankAccount=FALSE, $existParamCreditCard=FALSE, $edit = FALSE){
		$subscription = new stdClass();
		
		if ($edit == TRUE){
			if ($existParamBankAccount == TRUE){
				//In edition mode set the 'bankAccountId' property in the subscription
				$subscription->bankAccountId = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_ID);
			}
			if ($existParamCreditCard == TRUE){
				$creditCard = self::buildCreditCardForSubscription($parameters);
				if (isset($creditCard)){
					//In edition mode set the 'creditCard' object in the subscription
					$subscription->creditCard = $creditCard;
				}
			}
		}else {
			
			$subscription->trialDays = CommonRequestUtil::getParameter($parameters, PayUParameters::TRIAL_DAYS);
			$subscription->quantity = CommonRequestUtil::getParameter($parameters, PayUParameters::QUANTITY);
			$subscription->installments = CommonRequestUtil::getParameter($parameters, PayUParameters::INSTALLMENTS_NUMBER);
			
			$customer = PayUSubscriptionsRequestUtil::buildCustomer($parameters);
			
			//creates the credit card object and associate to the customer
			if ($existParamCreditCard == TRUE){
				$creditCard = self::buildCreditCardForSubscription($parameters);
				if (isset($creditCard)){
					$creditCards =  array($creditCard);
					$customer->creditCards = $creditCards;
				}
			}
			
			//creates the Bank Account object and associate to the customer
			if ($existParamBankAccount == TRUE){
				$bankAccount = self::buildBankAccountForSubscription($parameters);
				
				if (isset($bankAccount)){
					$bankAccounts = array($bankAccount);
					$customer->bankAccounts = $bankAccounts;
				}
			}
			
			$subscription->customer = $customer;
			$subscription->plan = PayUSubscriptionsRequestUtil::buildSubscriptionPlan($parameters);
			$subscription->plan->id = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_ID);
			
			$termsAndConditionsAcepted = CommonRequestUtil::getParameter($parameters, PayUParameters::TERMS_AND_CONDITIONS_ACEPTED);
			if (isset($termsAndConditionsAcepted)){
				$subscription->termsAndConditionsAcepted = $termsAndConditionsAcepted;
			}
 		}
		return $subscription;
	}
	
	/**
	 * Build the Credit card object for subscription
	 * @param array $parameters
	 */
	protected static function buildCreditCardForSubscription($parameters){
		$creditCard = null;
		$tokenId = CommonRequestUtil::getParameter($parameters, PayUParameters::TOKEN_ID);
		if(!isset($tokenId)){
			$creditCard = PayUSubscriptionsRequestUtil::buildCreditCard($parameters);
			$creditCard->customerId = NULL;
		}else{
			$creditCard = new stdClass();
			$creditCard->token = $tokenId;
			$creditCard->address = NULL;
		}
		return $creditCard;
	}

	/**
	 * Build the Credit card object for subscription
	 * @param array $parameters
	 */
	protected static function buildBankAccountForSubscription($parameters){
		$bankAccountId = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_ID);
		if(!isset($bankAccountId)){
			$bankAccount =RequestPaymentsUtil::buildBankAccountRequest($parameters);
			$bankAccount->customerId = NULL;
		}else{
			$bankAccount = new stdClass();
			$bankAccount->id = $bankAccountId;
		}
		return $bankAccount;
	}	
	
	/**
	 * Build a subscription plan request
	 * @param array $parameters
	 * @return stdClass with the subscription plan request built
	 */
	public static function buildSubscriptionPlan($parameters){
		
		$subscriptionPlan = new stdClass();
		
		$subscriptionPlan->accountId = CommonRequestUtil::getParameter($parameters, PayUParameters::ACCOUNT_ID);
		$subscriptionPlan->planCode = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_CODE);
		$subscriptionPlan->description = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_DESCRIPTION);
		$subscriptionPlan->interval = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_INTERVAL);
		$subscriptionPlan->intervalCount = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_INTERVAL_COUNT);
		$subscriptionPlan->trialDays = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_TRIAL_PERIOD_DAYS);
		$subscriptionPlan->maxPaymentsAllowed = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_MAX_PAYMENTS);
		$subscriptionPlan->paymentAttemptsDelay = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_ATTEMPTS_DELAY);
		$subscriptionPlan->maxPaymentAttempts = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_MAX_PAYMENT_ATTEMPTS);
		$subscriptionPlan->maxPendingPayments = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_MAX_PENDING_PAYMENTS);
		
		
		$planCurrency = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_CURRENCY);
		$planValue = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_VALUE);
		$planTaxValue = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_TAX);
		$planTaxReturnBase = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_TAX_RETURN_BASE);
		
		$subscriptionPlan->additionalValues = PayUSubscriptionsRequestUtil::buildSubscriptionPlanAdditionalValues($planCurrency, $planValue, $planTaxValue, $planTaxReturnBase);
		
		return $subscriptionPlan;
	}
	

	/**
	 * Build a customer request
	 * @param array $parameters
	 * @return stdClass with the customer request built
	 */
	public static function buildCustomer($parameters){
		
		$customer = new stdClass();
		$customer->fullName = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_NAME);
		$customer->email = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_EMAIL);
		$customer->id= CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		return $customer;
	}
	

	/**
	 * Build a credit card request
	 * @param array $parameters
	 * @return stdClass with the credit card request built
	 */
	public static function buildCreditCard($parameters){
		
		$creditCard = new stdClass();
		$creditCard->token = CommonRequestUtil::getParameter($parameters, PayUParameters::TOKEN_ID);
		$creditCard->customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		
		$creditCard->number = CommonRequestUtil::getParameter($parameters, PayUParameters::CREDIT_CARD_NUMBER);
		$creditCard->name = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_NAME);
		$creditCard->type = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYMENT_METHOD);
		$creditCard->document = CommonRequestUtil::getParameter($parameters, PayUParameters::CREDIT_CARD_DOCUMENT);
		
		$creditCard->address = PayUSubscriptionsRequestUtil::buildAddress($parameters);
		
		
		$expirationDate = CommonRequestUtil::getParameter($parameters, PayUParameters::CREDIT_CARD_EXPIRATION_DATE); 
		
		if (isset($expirationDate)){
			PayUSubscriptionsRequestUtil::isValidDate($expirationDate, 
													PayUConfig::PAYU_SECONDARY_DATE_FORMAT, 
													PayUParameters::CREDIT_CARD_EXPIRATION_DATE);
			$expirationDateSplit = explode('/',$expirationDate);
			$creditCard->expYear = $expirationDateSplit[0];
			$creditCard->expMonth = $expirationDateSplit[1];
		}
		
		return $creditCard;
	}
	
	
	/**
	 * Build an address object to be added to payment request
	 * @param array $parameters
	 * @return return an address
	 */
	private static function buildAddress($parameters){
		$address = new stdClass();
		$address->city = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_CITY);
		$address->country = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_COUNTRY);
		$address->phone = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_PHONE);
		$address->postalCode = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_POSTAL_CODE);
		$address->state = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_STATE);
		$address->line1 = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_STREET);
		$address->line2 = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_STREET_2);
		$address->line3 = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_STREET_3);
		return $address;
	}
	
	/**
	 * Build order additional values
	 * @param string $txCurrency
	 * @param string $txValue
	 * @param string $taxValue
	 * @param string $taxReturnBase
	 * @return the a map with the valid additional values
	 *
	 */
	private static function buildSubscriptionPlanAdditionalValues($planCurrency, $planValue, $planTaxValue, $planTaxReturnBase){
	
		$additionalValues = null;
	
		$additionalValues = PayUSubscriptionsRequestUtil::addAdditionalValue($additionalValues, $planCurrency, PayUKeyMapName::PLAN_VALUE, $planValue);
	
		$additionalValues = PayUSubscriptionsRequestUtil::addAdditionalValue($additionalValues, $planCurrency, PayUKeyMapName::PLAN_TAX, $planTaxValue);
	
		$additionalValues = PayUSubscriptionsRequestUtil::addAdditionalValue($additionalValues, $planCurrency, PayUKeyMapName::PLAN_TAX_RETURN_BASE, $planTaxReturnBase);
	
		return $additionalValues;
	
	}
	
	/**
	 * Build item additional values
	 * @param string $txCurrency
	 * @param string $txValue
	 * @param string $taxValue
	 * @param string $taxReturnBase
	 * @return the a map with the valid additional values
	 *
	 */
	private static function buildItemAdditionalValues($currency, $value, $taxValue, $taxReturnBase){
	
		$additionalValues = null;
	
		$additionalValues = PayUSubscriptionsRequestUtil::addAdditionalValue($additionalValues, $currency, PayUKeyMapName::ITEM_VALUE, $value);
	
		$additionalValues = PayUSubscriptionsRequestUtil::addAdditionalValue($additionalValues, $currency, PayUKeyMapName::ITEM_TAX, $taxValue);
	
		$additionalValues = PayUSubscriptionsRequestUtil::addAdditionalValue($additionalValues, $currency, PayUKeyMapName::ITEM_TAX_RETURN_BASE, $taxReturnBase);
	
		return $additionalValues;
	
	}
	
	
	
	
	/**
	 * Build a additional value and add it to container object
	 * @param Object $container
	 * @param string $txCurrency the code of the transaction currency
	 * @param string $txValueName the parameter name
	 * @param string $value the parameter value
	 * @return $container whith the valid additional values  added
	 *
	 */
	private static function addAdditionalValue($container, $txCurrency, $txValueName, $value){
	
		if($value != null && $txCurrency != null){
			if(!isset($container)){
				$container = array();
			}
			$additionalValue = new stdClass();
			$additionalValue->name = $txValueName;
			$additionalValue->value = $value;
			$additionalValue->currency = $txCurrency;
			array_push($container,$additionalValue);
		}
	
		return $container;
	}
	
	/**
	 * Build a PayUHttpRequestInfo with the information to the request
	 * @param string $urlSegment the url to complete the url to the api request
	 * @param string $lang the language to be sent in header information
	 * @param string $requestMethod the request method to be used
	 * @return PayUHttpRequestInfo the object build
	 */
	public static function buildHttpRequestInfo($urlSegment, $lang, $requestMethod){
		if(!isset($lang)){
			$lang = PayU::$language;
		}
	
		$payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::SUBSCRIPTIONS_API, $requestMethod, $urlSegment);
	
		$payUHttpRequestInfo->lang = $lang;
		$payUHttpRequestInfo->user = PayU::$apiLogin;
		$payUHttpRequestInfo->password = PayU::$apiKey;
	
		return $payUHttpRequestInfo;
	}
	
	
	/**
	 * Build a recurring bill item request
	 * @param array $parameters
	 * @return stdClass with the recurring bill item request built
	 */
	public static function buildRecurringBillItem($parameters){
		$recurringBillItem = new stdClass();
		$recurringBillItem->id = CommonRequestUtil::getParameter($parameters, PayUParameters::RECURRING_BILL_ITEM_ID);
		$recurringBillItem->description = CommonRequestUtil::getParameter($parameters, PayUParameters::DESCRIPTION);
		$recurringBillItem->subscriptionId = CommonRequestUtil::getParameter($parameters, PayUParameters::SUBSCRIPTION_ID);

		
		$currency = CommonRequestUtil::getParameter($parameters, PayUParameters::CURRENCY);
		$itemValue = CommonRequestUtil::getParameter($parameters, PayUParameters::ITEM_VALUE);
		$itemTaxValue = CommonRequestUtil::getParameter($parameters, PayUParameters::ITEM_TAX);
		$itemTaxReturnBaseValue = CommonRequestUtil::getParameter($parameters, PayUParameters::ITEM_TAX_RETURN_BASE);
		
		$recurringBillItem->additionalValues = 
								PayUSubscriptionsRequestUtil::buildItemAdditionalValues(
																	$currency, 
																	$itemValue, 
																	$itemTaxValue, 
																	$itemTaxReturnBaseValue);
		return $recurringBillItem;
	}
	
	
	/**
	 * Validate a subscription plan
	 * @param array $parameters
	 * @throws InvalidParameterException 
	 */
	public static function validateSubscriptionPlan($parameters){
	
		$required = array(PayUParameters::PLAN_INTERVAL, PayUParameters::PLAN_CODE,
				PayUParameters::PLAN_INTERVAL_COUNT, PayUParameters::PLAN_CURRENCY,
				PayUParameters::PLAN_VALUE, PayUParameters::ACCOUNT_ID,
				PayUParameters::PLAN_ATTEMPTS_DELAY, PayUParameters::PLAN_DESCRIPTION,
				PayUParameters::PLAN_MAX_PAYMENTS);
	
		$planId = CommonRequestUtil::getParameter($parameters, PayUParameters::PLAN_ID);
		
		if(isset($planId)){
			$invalid = $required;
			CommonRequestUtil::validateParameters($parameters,NULL,$invalid);
		}else{
			CommonRequestUtil::validateParameters($parameters,$required);
		}
	}
	
	/**
	 * Validate a customer in subscription request
	 * @param array $parameters
	 * @throws InvalidParameterException 
	 */
	public static function validateCustomerToSubscription($parameters, $edit = FALSE){
	
		$customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
	
		if(isset($customerId) || $edit == TRUE){
			$invalid = array(PayUParameters::CUSTOMER_EMAIL,PayUParameters::CUSTOMER_NAME);
			CommonRequestUtil::validateParameters($parameters,NULL,$invalid);
		}else{
			PayUSubscriptionsRequestUtil::validateCustomer($parameters, FALSE);
		}
	
	}
	
	
	/**
	 * Validate a customer 
	 * @param array $parameters
	 * @throws InvalidParameterException
	 */
	public static function validateCustomer($parameters, $edit = FALSE){
		
		$customerEmail = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_EMAIL);
		$customerName = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_NAME);

		if($edit){
			CommonRequestUtil::validateParameters($parameters, array(PayUParameters::CUSTOMER_ID));
		}
		
		if(!isset($customerEmail) && !isset ($customerName)){
			throw new InvalidArgumentException ( 'You must send the [' . PayUParameters::CUSTOMER_EMAIL .
					'] or [' . PayUParameters::CUSTOMER_NAME . '] value' );
		}
	}
	
	/**
	 * Validate a Credit Card or Token 
	 * @param array $parameters
	 * @throws InvalidParameterException
	 */
	public static function validateCreditCard($parameters){
		$tokenId = CommonRequestUtil::getParameter($parameters, PayUParameters::TOKEN_ID);
		if(isset($tokenId)){
			$required = array( PayUParameters::TOKEN_ID);
			$invalid = array(PayUParameters::CREDIT_CARD_NUMBER,
					PayUParameters::CREDIT_CARD_EXPIRATION_DATE, PayUParameters::PAYMENT_METHOD,
					PayUParameters::PAYER_NAME, PayUParameters::PAYER_STREET,
					PayUParameters::PAYER_STREET_2, PayUParameters::PAYER_STREET_3,
					PayUParameters::PAYER_CITY, PayUParameters::PAYER_STATE,
					PayUParameters::PAYER_COUNTRY, PayUParameters::PAYER_POSTAL_CODE,
					PayUParameters::PAYER_PHONE);
			
			CommonRequestUtil::validateParameters($parameters, $required, $invalid);
			
		}else{
			$required = array(PayUParameters::CREDIT_CARD_NUMBER,
					PayUParameters::PAYER_NAME,
					PayUParameters::PAYMENT_METHOD,
					PayUParameters::CREDIT_CARD_EXPIRATION_DATE);
				
			CommonRequestUtil::validateParameters($parameters, $required);
		}
	
	}
	
	/**
	 * Validate a Bank Account
	 * @param array $parameters
	 * @throws InvalidParameterException
	 */
	public static function validateBankAccount($parameters){
		$bankAccountId = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_ID);
		if(isset($bankAccountId)){
			$required = array( PayUParameters::BANK_ACCOUNT_ID);
			$invalid = array( PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER,
					PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER_TYPE,
					PayUParameters::BANK_ACCOUNT_CUSTOMER_NAME,
					PayUParameters::BANK_ACCOUNT_AGENCY_NUMBER,
					PayUParameters::BANK_ACCOUNT_AGENCY_DIGIT,
					PayUParameters::BANK_ACCOUNT_ACCOUNT_DIGIT,
					PayUParameters::BANK_ACCOUNT_NUMBER,
					PayUParameters::BANK_ACCOUNT_BANK_NAME,
					PayUParameters::BANK_ACCOUNT_TYPE,
					PayUParameters::BANK_ACCOUNT_STATE );
			CommonRequestUtil::validateParameters($parameters, $required, $invalid);
				
		}else{
			$required = array( PayUParameters::BANK_ACCOUNT_CUSTOMER_NAME,
					PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER,
					PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER_TYPE,
					PayUParameters::BANK_ACCOUNT_BANK_NAME,
					PayUParameters::BANK_ACCOUNT_TYPE,
					PayUParameters::BANK_ACCOUNT_NUMBER,
					PayUParameters::COUNTRY);
	
			CommonRequestUtil::validateParameters($parameters, $required);
		}
		
	}	
	
	
}
