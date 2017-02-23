<?php

/**
 *
 * Utility class to process parameters and send payment requests
 * 
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */
class RequestPaymentsUtil extends CommonRequestUtil{
	
	
	
	
	/**
	 * Build a ping request
	 * @param string $lang language to be used 
	 * @return the ping request built
	 */
	static function buildPingRequest($lang=null){
		
		if(!isset($lang)){
			$lang = PayU::$language;
		}
	
		$request = CommonRequestUtil::buildCommonRequest($lang,
				PayUCommands::PING);
	
		return $request;
	}	
	
	/**
	 * Builds the payment method list request
	 * @param string $lang language to be used
	 * @return the request built
	 */
	public static function buildPaymentMethodsListRequest($lang = null){
	
		if(!isset($lang)){
			$lang = PayU::$language;
		}
		
		$request = CommonRequestUtil::buildCommonRequest($lang,
				PayUCommands::GET_PAYMENT_METHODS);
		
		return $request;
		
	}
	
	
	/**
	 * Builds a get bank list request
	 * 
	 * @param string $paymentCountry the payment country 
	 * @param string $lang language to be used
	 * 
	 * @return The complete bank list response
	 */
	public static function buildBankListRequest($paymentCountry, $lang = null) {
		
		if(!isset($lang)){
			$lang = PayU::$language;
		}
		
		$request = CommonRequestUtil::buildCommonRequest($lang,
				PayUCommands::GET_BANKS_LIST);
		
		$request->bankListInformation = new stdClass();
		
		$request->bankListInformation->paymentMethod = PaymentMethods::PSE;
		$request->bankListInformation->paymentCountry = $paymentCountry;

		return $request;
	}	
	

	/**
	 * Builds a Payment Method Availability Request
	 *
	 * @param string $paymentMethodParameter the payment method
	 * @param string $lang language to be used
	 *
	 * @return The complete payment method information from API
	 */
	public static function buildPaymentMethodAvailabilityRequest($paymentMethodParameter, $lang = null) {
	
		if(!isset($lang)){
			$lang = PayU::$language;
		}
		
		$request = CommonRequestUtil::buildCommonRequest($lang,
				PayUCommands::GET_PAYMENT_METHOD_AVAILABILITY);
		
		$request->paymentMethod = $paymentMethodParameter;
	
		return $request;
	}
	
	/**
	 * Build a payment request
	 * @param array $parameters the parameters to build a request
	 * @param string $transactionType the transaction type
	 * @param strng $lang to be used
	 * @return the request built
	 */	
	static function buildPaymentRequest($parameters, $transactionType, $lang = null){
		
		if(!isset($lang)){
			$lang = PayU::$language;
		}
		
		$request = CommonRequestUtil::buildCommonRequest($lang, 
					PayUCommands::SUBMIT_TRANSACTION);
		
		$transaction = null;
		
		if (TransactionType::AUTHORIZATION_AND_CAPTURE == $transactionType
		|| TransactionType::AUTHORIZATION == $transactionType) {
			
			$transaction = RequestPaymentsUtil::buildTransactionRequest($parameters, $lang);
			
		}else if (TransactionType::VOID == $transactionType
				|| TransactionType::REFUND == $transactionType
				|| TransactionType::CAPTURE == $transactionType) {
			
			$transaction = RequestPaymentsUtil::buildTransactionRequestAfterAuthorization($parameters);
			
		}

		$transaction->type = $transactionType;
		$request->transaction = $transaction;
		return $request;
		
	}
	
	
	/**
	 * Build a transaction object to be added to payment request
	 * @param array $parameters the parameters to build a transaction
	 * @param strng $lang to be used
	 * @return the transaction built
	 * @throws InvalidArgumentException if any paramter is invalid
	 * 
	 */
	private static function buildTransactionRequest($parameters, $lang){
		$transaction = new stdClass();
		$order = null;
		
			$transaction->paymentCountry = CommonRequestUtil::getParameter($parameters, PayUParameters::COUNTRY);

			
			if(CommonRequestUtil::getParameter($parameters, PayUParameters::ORDER_ID) == null ){
				$signature = null;
				if(CommonRequestUtil::getParameter($parameters, PayUParameters::SIGNATURE) != null){
					$signature = CommonRequestUtil::getParameter($parameters, PayUParameters::SIGNATURE);
				}
				
				$merchantId = PayU::$merchantId;
				$order = RequestPaymentsUtil::buildOrderRequest($parameters, $lang);
				
				if ($signature == null && $merchantId != null) {
					$signature = SignatureUtil::buildSignature($order, $merchantId, PayU::$apiKey, SignatureUtil::MD5_ALGORITHM);
				}
				$order->signature = $signature;
				$transaction->order = $order;
			}else{
				$orderId = CommonRequestUtil::getParameter($parameters, PayUParameters::ORDER_ID);
				$order = new stdClass();
				$order->orderId($orderId);
				$transaction.setOrder($order);
			}
			
			$transaction->order->buyer = RequestPaymentsUtil::buildBuyer($parameters);

			if(CommonRequestUtil::getParameter($parameters, PayUParameters::IP_ADDRESS) != null){
				$transaction->ipAddress = CommonRequestUtil::getParameter($parameters, PayUParameters::IP_ADDRESS);
			}

			if(CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_COOKIE) != null){
				$transaction->cookie = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_COOKIE);
			}else{
				$transaction->cookie = 'cookie_' . microtime();
			}
				
			$transaction->deviceSessionId = CommonRequestUtil::getParameter($parameters, PayUParameters::DEVICE_SESSION_ID);
									


			if(CommonRequestUtil::getParameter($parameters, PayUParameters::USER_AGENT) != null){
				$transaction->userAgent = CommonRequestUtil::getParameter($parameters, PayUParameters::USER_AGENT);
			}else{
				$transaction->userAgent = sprintf("%s %s", PayU::API_NAME,PayU::API_VERSION);
			}
			
			$transaction->source = PayU::API_CODE_NAME;
			
			if( CommonRequestUtil::getParameter($parameters, PayUParameters::CREDIT_CARD_NUMBER) != null ){
				$transaction->creditCard = RequestPaymentsUtil::buildCreditCardTransaction($transaction, $parameters);
			}else if( CommonRequestUtil::getParameter($parameters, PayUParameters::TOKEN_ID) != null) {
				$transaction->creditCard = RequestPaymentsUtil::buildCreditCardForToken($parameters);
			}
			
			
			if( CommonRequestUtil::getParameter($parameters, PayUParameters::INSTALLMENTS_NUMBER) != null) {
				$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
						PayUKeyMapName::TRANSACTION_INSTALLMENTS_NUMBER,
						CommonRequestUtil::getParameter($parameters, PayUParameters::INSTALLMENTS_NUMBER));
			}
				
			if( CommonRequestUtil::getParameter($parameters, PayUParameters::RESPONSE_URL) != null) {
				$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
						PayUKeyMapName::RESPONSE_URL,
						CommonRequestUtil::getParameter($parameters, PayUParameters::RESPONSE_URL));
			}
			
			$expirationDate = CommonRequestUtil::getParameter($parameters,PayUParameters::EXPIRATION_DATE); 
			if(isset($expirationDate) && CommonRequestUtil::isValidDate($expirationDate,PayUConfig::PAYU_DATE_FORMAT, PayUParameters::EXPIRATION_DATE)){
				$transaction->expirationDate = $expirationDate;
			}
			
			$transaction->creditCardTokenId = CommonRequestUtil::getParameter($parameters, PayUParameters::TOKEN_ID);
			
			$paymentMethod = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYMENT_METHOD);
			
			// PSE extra parameters
			if ("PSE" == $paymentMethod) {
				$transaction = RequestPaymentsUtil::addPSEExtraParameters($transaction, $parameters);
			}				
			
			$transaction->paymentMethod = $paymentMethod;
			
			$transaction->payer = RequestPaymentsUtil::buildPayer($parameters);
				
		$transaction->order = $order;
		return $transaction;
		
	}
	
	
	
	/**
	 * Build a transaction object to be added to payment request
	 * this build a transaction request to after authorization o authorization and capture 
	 * @param array $parameters the parameters to build a transaction
	 * @return the transaction built
	 */
	private static function buildTransactionRequestAfterAuthorization($parameters){
		
		$transaction = new stdClass();
		$transaction->parentTransactionId = CommonRequestUtil::getParameter($parameters, PayUParameters::TRANSACTION_ID);
		$transaction->reason = CommonRequestUtil::getParameter($parameters, PayUParameters::REASON);
		
		$order = new stdClass();
		$order->id = CommonRequestUtil::getParameter($parameters, PayUParameters::ORDER_ID);
		
		$transaction->order = $order;
		
		return $transaction;
	}

	/**
	 * Build a order object to be added to payment request  
	 * @param array $parameters the parameters to build a object
	 * @param string $lang
	 * @return the order built
	 */
	private static function buildOrderRequest($parameters, $lang){
		
		
		$order = new stdClass();
		$order->accountId = CommonRequestUtil::getParameter($parameters, PayUParameters::ACCOUNT_ID);
		$order = RequestPaymentsUtil::addOrderBasicData($order, $parameters, $lang);
		$order->notifyUrl = CommonRequestUtil::getParameter($parameters, PayUParameters::NOTIFY_URL);
		$order->additionalValues = 
		RequestPaymentsUtil::buildOrderAdditionalValues(CommonRequestUtil::getParameter($parameters, PayUParameters::CURRENCY),
												CommonRequestUtil::getParameter($parameters, PayUParameters::VALUE),
												CommonRequestUtil::getParameter($parameters, PayUParameters::TAX_VALUE),
												CommonRequestUtil::getParameter($parameters, PayUParameters::TAX_RETURN_BASE)
												);
		
		return $order;
		
	}
	
	/**
	 * Adds to order object the basic data
	 * @param object $order
	 * @param array $parameters the parameters to build a object
	 * @param string $lang to be used
	 * @return the order with the basic information added
	 * 
	 */
	private static function addOrderBasicData($order, $parameters, $lang){
		if(!isset($order)){
			$order = new stdClass();
		}
		
		$order->referenceCode = CommonRequestUtil::getParameter($parameters, PayUParameters::REFERENCE_CODE);
		$order->description = CommonRequestUtil::getParameter($parameters, PayUParameters::DESCRIPTION);
		$order->language = $lang;
		
		return $order;
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
	private static function buildOrderAdditionalValues($txCurrency, $txValue, $taxValue, $taxReturnBase){
		
		$additionalValues = new stdClass();
		
		$additionalValues = RequestPaymentsUtil::addAdditionalValue($additionalValues, $txCurrency, PayUKeyMapName::TX_VALUE, $txValue);
		
		$additionalValues = RequestPaymentsUtil::addAdditionalValue($additionalValues, $txCurrency, PayUKeyMapName::TX_TAX, $taxValue);
		
		$additionalValues = RequestPaymentsUtil::addAdditionalValue($additionalValues, $txCurrency, PayUKeyMapName::TX_TAX_RETURN_BASE, $taxReturnBase);
		
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
		
		if($value != null){
			$additionalValue = new stdClass();
			$additionalValue->value = $value;
			$additionalValue->currency = $txCurrency;
			
			$container->$txValueName = $additionalValue;
		}
		
		return $container;
		
	}
	
	
	/**
	 * Build a additional value and add it to container object
	 * @param Object $container
	 * @param string $name the name of parameter
	 * @param string $value the parameter value
	 * @return $container whith the valid extra parameters added
	 *
	 */
	private static function addExtraParameter($container, $name, $value){
		if( !isset($container->extraParameters) ){
			$container->extraParameters = new stdClass();
		}
	
		if(isset($value) && isset($name)){
			$extraParameter = new stdClass();
			$extraParameter->value = $value;
			$container->extraParameters->$name = $value;
		}
		
		return $container;
	}
	
	/**
	 * Build a buyer object to be added to payment request  
	 * @param array $parameters
	 * @return return a buyer
	 */
	private static function buildBuyer($parameters){
		$buyer = new stdClass();
		$buyer->fullName = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_NAME);
		$buyer->emailAddress = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_EMAIL);
		$buyer->cnpj = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_CNPJ);
		$buyer->contactPhone = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_CONTACT_PHONE);
		$buyer->dniNumber = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_DNI);
		
		$buyer->shippingAddress = RequestPaymentsUtil::buildBuyerAddress($parameters);
		
		return $buyer;
	}
	
	/**
	 * Build a payer object to be added to payment request
	 * @param array $parameters
	 * @return return a payer
	 */
	private static function buildPayer($parameters){
		$payer = new stdClass();
		$payer->fullName = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_NAME);
		$payer->emailAddress = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_EMAIL);
		$payer->cnpj = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_CNPJ);
		$payer->contactPhone = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_CONTACT_PHONE);
		$payer->dniNumber = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_DNI);
		$payer->dniType = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_DNI_TYPE);
		$payer->businessName = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_BUSINESS_NAME);
		
		$payerBirthDay = CommonRequestUtil::getParameter($parameters,PayUParameters::PAYER_BIRTHDATE);
		if(isset($payerBirthDay) && CommonRequestUtil::isValidDate($payerBirthDay,PayUConfig::PAYU_DAY_FORMAT, PayUParameters::PAYER_BIRTHDATE)){
			$payer->birthdate = $payerBirthDay;
		}
		
		$payer->billingAddress = RequestPaymentsUtil::buildAddress($parameters);		
		
		return $payer;
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
		$address->postalCode = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_POSTAL_CODE);
		$address->street1 = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_STREET);
		$address->street2 = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_STREET_2);
		$address->street3 = CommonRequestUtil::getParameter($parameters, PayUParameters::PAYER_STREET_3);
		
		return $address;
	}
	
	/**
	 * Build an address object to be added to payment request
	 * @param array $parameters
	 * @return return an address
	 */
	private static function buildBuyerAddress($parameters){
		
		$address = new stdClass();
		$address->city = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_CITY);
		$address->country = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_COUNTRY);
		$address->phone = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_PHONE);
		$address->postalCode = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_POSTAL_CODE);
		$address->state = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_STATE);
		$address->postalCode = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_POSTAL_CODE);
		$address->street1 = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_STREET);
		$address->street2 = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_STREET_2);
		$address->street3 = CommonRequestUtil::getParameter($parameters, PayUParameters::BUYER_STREET_3);

		return $address;
	}
	
	/**
	 * Build a credit card object to be added to payment request
	 * @param object $transaction the transaction where the credit card will be added
	 * @param object $parameters with the credit card info
	 * @return the credit built
	 */
	private static function buildCreditCardTransaction($transaction, $parameters){
		return CommonRequestUtil::buildCreditCard($parameters);
	}

	/**
	 * Build a credit card object to be added to payment request when is used token for payments
	 * @param object $parameters with the credit card info
	 * @return the credit card built 
	 */
	private static function buildCreditCardForToken($parameters){
		$creditCard = new stdClass();
		$creditCard->securityCode = CommonRequestUtil::getParameter($parameters, PayUParameters::CREDIT_CARD_SECURITY_CODE);
		return $creditCard;
	}
	
	/**
	 * Adds the extra parameters required by the PSE payment method
	 *
	 * @param transaction
	 * @param parameters
	 * @throws InvalidParametersException
	 */
	private static function addPSEExtraParameters($transaction, $parameters){
	
		// PSE reference identification 1
		$pseReference1 = CommonRequestUtil::getParameter($parameters,
		PayUParameters::IP_ADDRESS);
	
		// PSE reference identification 2
		$pseReference2 = CommonRequestUtil::getParameter($parameters,
		PayUParameters::PAYER_DOCUMENT_TYPE);
	
		// PSE reference identification 3
		$pseReference3 = CommonRequestUtil::getParameter($parameters,
		PayUParameters::PAYER_DNI);
	
		// PSE user type N-J (Natural or Legal)
		$pseUserType = CommonRequestUtil::getParameter($parameters,
		PayUParameters::PAYER_PERSON_TYPE);		
	
		// PSE financial institution code (Bank code)
		$pseFinancialInstitutionCode = CommonRequestUtil::getParameter($parameters,
		PayUParameters::PSE_FINANCIAL_INSTITUTION_CODE);
	
		// PSE financial institution name (Bank Name)
		$pseFinancialInstitutionName = CommonRequestUtil::getParameter($parameters,
		PayUParameters::PSE_FINANCIAL_INSTITUTION_NAME);
	
		if ($pseFinancialInstitutionCode != null) {
			$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
					PayUKeyMapName::FINANCIAL_INSTITUTION_CODE, $pseFinancialInstitutionCode);
		}
	
		if ($pseFinancialInstitutionName != null) {
			$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
					PayUKeyMapName::FINANCIAL_INSTITUTION_NAME, $pseFinancialInstitutionName);		
		}
	
		if ($pseUserType != null) {
			$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
					PayUKeyMapName::USER_TYPE, $pseUserType);			
		}
	
		if ($pseReference1 != null) {
			$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
					PayUKeyMapName::PSE_REFERENCE1, $pseReference1);			
		}
	
		if ($pseReference2 != null) {
			$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
					PayUKeyMapName::PSE_REFERENCE2, $pseReference2);
		}
	
		if ($pseReference3 != null) {
			$transaction = RequestPaymentsUtil::addExtraParameter($transaction,
					PayUKeyMapName::PSE_REFERENCE3, $pseReference3);			
		}
		
		return $transaction;
	
	}
	
	
	

	/**
	 * Build a bank account request
	 * @param array $parameters
	 * @return stdClass with the bank account request built
	 */
	public static function buildBankAccountRequest($parameters){
		$bankAccount = new stdClass();
	
		$bankAccount->id = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_ID);
		$bankAccount->accountId = CommonRequestUtil::getParameter($parameters, PayUParameters::ACCOUNT_ID );
		$bankAccount->customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
		$bankAccount->name = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_CUSTOMER_NAME );
		$bankAccount->documentNumber = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER );
		$bankAccount->documentNumberType = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_DOCUMENT_NUMBER_TYPE );
		$bankAccount->bank = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_BANK_NAME );
		$bankAccount->type = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_TYPE );
		$bankAccount->accountNumber = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_NUMBER );
		$bankAccount->state = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_STATE );
		$bankAccount->country = CommonRequestUtil::getParameter($parameters, PayUParameters::COUNTRY );
		$bankAccount->agencyDigit = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_AGENCY_DIGIT );
		$bankAccount->agencyNumber = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_AGENCY_NUMBER );
		$bankAccount->accountDigit = CommonRequestUtil::getParameter($parameters, PayUParameters::BANK_ACCOUNT_ACCOUNT_DIGIT );
	
		return $bankAccount;
	}
	
	/**
	 * Builds a bank account list request
	 *
	 * @param parameters The parameters to be sent to the server
	 * @return stdClass with the bank account list request built
	 */
	public static function buildBankAccountListRequest($parameters) {
		$bankAccountListRequest = new stdClass();
	
		$bankAccountListRequest->customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
	
		return $bankAccountListRequest;
	}
		
	

}