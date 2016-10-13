<?php

/**
 * This class contains the payments methods 
 * availables in payu platform
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */

class PaymentMethods{
	
	const VISA= 'VISA';
	const AMEX= 'AMEX';
	const DINERS= 'DINERS';
	const MASTERCARD= 'MASTERCARD';
	const DISCOVER= 'DISCOVER';
	const ELO= 'ELO';
	const PSE= 'PSE';
	const BALOTO= 'BALOTO';
	const EFECTY= 'EFECTY';
	const BCP= 'BCP';
	const SEVEN_ELEVEN= 'SEVEN_ELEVEN';
	const OXXO= 'OXXO';
	const BOLETO_BANCARIO= 'BOLETO_BANCARIO';
	const RAPIPAGO= 'RAPIPAGO';
	const PAGOFACIL= 'PAGOFACIL';
	const BAPRO= 'BAPRO';
	const COBRO_EXPRESS= 'COBRO_EXPRESS';
	const SERVIPAG= 'SERVIPAG';
	const BANK_REFERENCED= 'BANK_REFERENCED';
	const VISANET= 'VISANET';
	const RIPSA= 'RIPSA';
	
	
	/**
	 * payment methods availables in payu including its payment method type
	 * 
	 */
	private static $methods = array(
		PaymentMethods::VISA=>array('name'=>PaymentMethods::VISA,'type'=>PayUPaymentMethodType::CREDIT_CARD),
			
		PaymentMethods::AMEX=>array('name'=>PaymentMethods::AMEX,'type'=>PayUPaymentMethodType::CREDIT_CARD),
			
		PaymentMethods::DINERS=>array('name'=>PaymentMethods::DINERS,'type'=>PayUPaymentMethodType::CREDIT_CARD),
			
		PaymentMethods::MASTERCARD=>array('name'=>PaymentMethods::MASTERCARD,'type'=>PayUPaymentMethodType::CREDIT_CARD),
			
		PaymentMethods::DISCOVER=>array('name'=>PaymentMethods::DISCOVER,'type'=>PayUPaymentMethodType::CREDIT_CARD),
			
		PaymentMethods::ELO=>array('name'=>PaymentMethods::ELO,'type'=>PayUPaymentMethodType::CREDIT_CARD),
			
		PaymentMethods::PSE=>array('name'=>PaymentMethods::PSE,'type'=>PayUPaymentMethodType::PSE),
			
		PaymentMethods::BALOTO=>array('name'=>PaymentMethods::BALOTO,'type'=>PayUPaymentMethodType::CASH),
		
		PaymentMethods::EFECTY=>array('name'=>PaymentMethods::EFECTY,'type'=>PayUPaymentMethodType::CASH),
			
		PaymentMethods::BCP=>array('name'=>PaymentMethods::BCP,'type'=>PayUPaymentMethodType::CASH),
			
		PaymentMethods::SEVEN_ELEVEN=>array('name'=>PaymentMethods::SEVEN_ELEVEN,'type'=>PayUPaymentMethodType::REFERENCED),
			
		PaymentMethods::OXXO=>array('name'=>PaymentMethods::OXXO,'type'=>PayUPaymentMethodType::REFERENCED),
			
		PaymentMethods::BOLETO_BANCARIO=>array('name'=>PaymentMethods::BOLETO_BANCARIO,'type'=>PayUPaymentMethodType::BOLETO_BANCARIO),
			
		PaymentMethods::RAPIPAGO=>array('name'=>PaymentMethods::RAPIPAGO,'type'=>PayUPaymentMethodType::CASH),
			
		PaymentMethods::PAGOFACIL=>array('name'=>PaymentMethods::PAGOFACIL,'type'=>PayUPaymentMethodType::CASH),
			
		PaymentMethods::BAPRO=>array('name'=>PaymentMethods::BAPRO,'type'=>PayUPaymentMethodType::CASH),'BAPRO',
			
		PaymentMethods::COBRO_EXPRESS=>array('name'=>PaymentMethods::COBRO_EXPRESS,'type'=>PayUPaymentMethodType::CASH),
			
		PaymentMethods::SERVIPAG=>array('name'=>PaymentMethods::SERVIPAG,'type'=>PayUPaymentMethodType::CASH),
			
		PaymentMethods::BANK_REFERENCED=>array('name'=>PaymentMethods::BANK_REFERENCED,'type'=>PayUPaymentMethodType::BANK_REFERENCED),
			
		PaymentMethods::VISANET=>array('name'=>PaymentMethods::VISANET,'type'=>PayUPaymentMethodType::CREDIT_CARD),
		
		PaymentMethods::RIPSA=>array('name'=>PaymentMethods::RIPSA,'type'=>PayUPaymentMethodType::CASH),
	);
	
	/**
	 * Allowed cash payment methods available in the api
	 */
	private static $allowedCashPaymentMethods = array(
			PaymentMethods::EFECTY,
			PaymentMethods::BALOTO,
			PaymentMethods::BCP,
			PaymentMethods::OXXO,
			PaymentMethods::RIPSA
	);
	
	
	/**
	 * validates if a payment method exist in payu platform 
	 * @param string $paymentMethod
	 * @return true if the payment method exist false the otherwise
	 */
	static function isValidPaymentMethod($paymentMethod){
		return array_key_exists($paymentMethod, PaymentMethods::$methods);
	}
	
	/**
	 * Returns the payment method info
	 * @param string $paymentMethod
	 * @return paymentMethod
	 */
	static function getPaymentMethod($paymentMethod){
		return PaymentMethods::$methods[$paymentMethod];
	}
	
	/**
	 * verify if the cash payment method is valid to process payments
	 * by api
	 * @param string $paymentMethod
	 * @return boolean
	 */
	static function isAllowedCashPaymentMethod($paymentMethod){
		return in_array($paymentMethod, PaymentMethods::$allowedCashPaymentMethods);
	}
	
	
	
}