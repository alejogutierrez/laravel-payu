<?php

/**
 *
 * Contains the parameters names 
 * used in the differents methods
 * 
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 * 
 */
class PayUParameters {

	/** The payment method's country. */
	const COUNTRY = 'country';
	/** The order's id. */
	const ORDER_ID = 'orderId';
	/** El order's reference code. */
	const REFERENCE_CODE = 'referenceCode';
	/** The order's or payment method's description. */
	const DESCRIPTION = 'description';
	/** The account's id. */
	const ACCOUNT_ID = 'accountId';
	/** The ISO code for the currency to use. */
	const CURRENCY = 'currency';
	/** The value of the purchase. */
	const VALUE = 'value';
	/** The tax value. */
	const TAX_VALUE = 'taxValue';
	/** The tax return base. */
	const TAX_RETURN_BASE = 'taxDevolutionBase';
	/** The tranaction signature. */
	const SIGNATURE = 'signature';
	/** the transaction id */
	const TRANSACTION_ID = 'transactionId';
	/** The credit card token id. */
	const TOKEN_ID = 'creditCardTokenId';
	/** the batch token id. */
	const BATCH_TOKEN_ID = 'batchTokenId';
	/** the Device Session id. */
	const DEVICE_SESSION_ID = 'deviceSessionId';
	/** The User Agent */
	const USER_AGENT = 'userAgent';
	/** The payment method to use. */
	const PAYMENT_METHOD = 'paymentMethod';
	/** The reason for cancellation of transaction. */
	const REASON = 'reason';
	

	/** The number of installments on the purchase. */
	const INSTALLMENTS_NUMBER = 'installmentsNumber';
	/** The number on the credit card. */
	const CREDIT_CARD_NUMBER = 'creditCardNumber';
	/** The credit card's expiration date. */
	const CREDIT_CARD_EXPIRATION_DATE = 'creditCardExpirationDate';
	/** The credit card's security code. */
	const CREDIT_CARD_SECURITY_CODE = 'creditCardSecurityCode';
	/** The credit card's document. */
	const CREDIT_CARD_DOCUMENT = 'creditCardDocument';

	/** The payer's name */
	const PAYER_NAME = 'payerName';
	/** The payer's id on the merchant. */
	const PAYER_ID = 'payerId';
	/** The payer's contact e-mail. */
	const PAYER_EMAIL = 'payerEmail';
	/** The payer's CNPJ. */	
	const PAYER_CNPJ = 'payerCNPJ';
	/** The payer's contact phone. */	
	const PAYER_CONTACT_PHONE = 'payerContactPhone';
	/** The payer's DNI. */	
	const PAYER_DNI = 'payerDNI';
	/** The payer's DNI type. */
	const PAYER_DNI_TYPE = "payerDNIType";
	/** The payer's Business Name */
	const PAYER_BUSINESS_NAME = "payerBusinessName";
	/** The payer's birth day */
	const PAYER_BIRTHDATE = "payerBirthDate";
	
	

	
	/** The payer's city. */
	const PAYER_CITY = 'payerCity';
	/** The payer's country. */
	const PAYER_COUNTRY = 'payerCountry';
	/** The payer's phone. */	
	const PAYER_PHONE = 'payerPhone';
	/** The payer's postal code. */	
	const PAYER_POSTAL_CODE = 'payerPostalCode';
	/** The payer's state. */
	const PAYER_STATE = 'payerState';
	/** The payer's address (part 1). */
	const PAYER_STREET = 'payerStreet';
	/** The payer's address (part 2). */
	const PAYER_STREET_2 = 'payerStreet2';
	/** The payer's address (part 3). */
	const PAYER_STREET_3 = 'payerStreet3';
	
	
	/** The buyers name */
	const BUYER_NAME = 'buyerName';
	/** The buyer's id on the merchant. */
	const BUYER_ID = 'buyerId';
	/** The buyer's contact e-mail. */
	const BUYER_EMAIL = 'buyerEmail';
	/** The buyer's CNPJ. */
	const BUYER_CNPJ = 'buyerCNPJ';
	/** The buyer's contact phone. */
	const BUYER_CONTACT_PHONE = 'buyerContactPhone';
	/** The buyer's DNI. */
	const BUYER_DNI = 'payerDNI';
	
	/** The buyer's city. */
	const BUYER_CITY = 'buyerCity';
	/** The buyer's country. */
	const BUYER_COUNTRY = 'buyerCountry';
	/** The buyer's phone. */
	const BUYER_PHONE = 'buyerPhone';
	/** The buyer's postal code. */
	const BUYER_POSTAL_CODE = 'buyerPostalCode';
	/** The buyer's state. */
	const BUYER_STATE = 'buyerState';
	/** The buyer's address (part 1). */
	const BUYER_STREET = 'buyerStreet';
	/** The buyer's address (part 2). */
	const BUYER_STREET_2 = 'buyerStreet2';
	/** The buyer's address (part 3). */
	const BUYER_STREET_3 = 'buyerStreet3';
	
	
	/** The payer's Ip address*/
	const IP_ADDRESS = 'ipAddress';
	
	const PAYER_COOKIE = 'PAYER_COOKIE';	

	/** Start date to filter. */
	const START_DATE = 'startDate';
	/** Last date to filter. */
	const END_DATE = 'endDate';

	/** The credit card's expiration date. */
	const EXPIRATION_DATE = 'expirationDate';

	/** The customer's name. */
	const CUSTOMER_NAME = 'customerName';
	/** The customer's id on the merchant. */
	const CUSTOMER_ID = 'customerId';
	/** The customer's contact e-mail. */
	const CUSTOMER_EMAIL = 'customerEmail';
	
	

	/** The plan id. */
	const PLAN_ID = 'planId';
	/** The plan code. */
	const PLAN_CODE = 'planCode';
	/** The plan description. */
	const PLAN_DESCRIPTION = 'planDescription';
	/** The plan interval. */
	const PLAN_INTERVAL = 'planInterval';
	/** The plan interval count. */
	const PLAN_INTERVAL_COUNT = 'planIntervalCount';
	/** The number of trial days of the plan. */
	const PLAN_TRIAL_PERIOD_DAYS = 'planTrialPeriodDays';
	/** The currency to use on the plan. */
	const PLAN_CURRENCY = 'planCurrency';
	/** The plan value. */
	const PLAN_VALUE = 'planValue';
	/** The plan tax. */
	const PLAN_TAX = 'planTax';
	/** The plan tax return base. */
	const PLAN_TAX_RETURN_BASE = 'planTaxReturnBase';
	/** The plan maximum number of payments. */
	const PLAN_MAX_PAYMENTS = 'maxPaymentsAllowed';
	/** The plan attempts delay. */
	const PLAN_ATTEMPTS_DELAY = "paymentAttemptsDelay";
	
	/** The plan max payments attempts delay. */
	const PLAN_MAX_PAYMENT_ATTEMPTS = 'maxPaymentAttempts';
	/** The plan max pending payments. */
	const PLAN_MAX_PENDING_PAYMENTS = 'maxPendingPayments';
	

	/** The subscription id. */
	const SUBSCRIPTION_ID = 'subscriptionId';

	/** The number of trial days. */
	const TRIAL_DAYS = 'trialDays';
	/** The quantity to purchase. */
	const QUANTITY = 'quantity';
	
	/** If the client accepted the terms and conditions document. */
	const TERMS_AND_CONDITIONS_ACEPTED = "termsAndConditionsAcepted";
		
	/** The state of the subscription*/
	const SUBSCRIPTION_STATE = 'state';

	/** The recurring bill item id. */
	const RECURRING_BILL_ITEM_ID = 'recurringBillItemId';
	/** The item value. */
	const ITEM_VALUE = 'itemValue';
	/** The tax to apply over the item. */
	const ITEM_TAX = 'itemTax';
	/** The item tax return base. */
	const ITEM_TAX_RETURN_BASE = 'itemTaxReturnBase';
	
	/**	Whether a transaction must process the cvv2 field */
	const PROCESS_WITHOUT_CVV2 = 'processWithoutCvv2';
	
	/** PSE payer ISO document type */
	const PAYER_DOCUMENT_TYPE='payerDocumentType';
	/** PSE payer person type (N - Natural or J - Legal) */
	const PAYER_PERSON_TYPE='payerPersonType';
	/** PSE financial institution code (Bank code) */
	const PSE_FINANCIAL_INSTITUTION_CODE='pseFinancialInstitutionCode';
	/** PSE financial institution name (Bank Name) */
	const PSE_FINANCIAL_INSTITUTION_NAME = "pseFinancialInstitutionName";

	//Params for Bank Accounts for Paymnet Plans 
	/** Id of the account holder */
	const BANK_ACCOUNT_ID = 'bankAccountId';
	/** Document Number of the account holder */
	const BANK_ACCOUNT_DOCUMENT_NUMBER = 'documentNumber';
	/** Document number type of the account holder */
	const BANK_ACCOUNT_DOCUMENT_NUMBER_TYPE = 'documentNumberType';
	/** ACH Bank */
	const BANK_ACCOUNT_BANK_NAME = 'bank';
	/** ACH Bank */
	const BANK_ACCOUNT_CUSTOMER_NAME = 'name';
	/** ACH bank account type */
	const BANK_ACCOUNT_TYPE = 'type';
	/** ACH bank account number */
	const BANK_ACCOUNT_NUMBER = 'accountNumber';
	/** ACH bank state */
	const BANK_ACCOUNT_STATE = 'accountState';
	/** Account digit (Only valid for Brazil) */
	const BANK_ACCOUNT_ACCOUNT_DIGIT = 'accountDigit';
	/** Agency digit (Only valid for Brazil) */
	const BANK_ACCOUNT_AGENCY_DIGIT = 'agencyDigit';
	/** Agency number (Only valid for Brazil) */
	const BANK_ACCOUNT_AGENCY_NUMBER = 'agencyNumber';
	/** The recurring bill id. */
	const RECURRING_BILL_ID = 'recurringBillId';
	/** The recurring bill date begin. */
	const RECURRING_BILL_DATE_BEGIN = 'dateBegin';
	/** The recurring bill date final. */
	const RECURRING_BILL_DATE_FINAL = 'dateFinal';
	/** The recurring bill state. */
	const RECURRING_BILL_STATE = 'state';
	/** the recurring bill payment method type */
	const RECURRING_BILL_PAYMENT_METHOD_TYPE = 'paymentMethod';
	
	
		
	/**
	 * Limits the amount of results in a query
	 */
	const LIMIT = "limit";
	
	/**
	 * Specifies where to start in a query with many results
	 */
	const OFFSET = "offset";
	
	/**
	 * Specifies whether an account is active or not
	 */
	const ACCOUNT_STATE = "state";
	
	/** 
	 * The confirmation page URL 
	 */
	const  NOTIFY_URL = "notifyUrl";
	
	/** 
	 * The response page URL 
	 */
	const RESPONSE_URL = "responseUrl";

}





