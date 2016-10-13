<?php

/**
 * 
 * Payu exception throw when the api service report an error
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0
 * 
 */
class PayUException extends Exception{
	
	public $payUCode;
	
	
	/**
	 * constructor method
	 * @param string $payuCode a element of PayUErrorCodes
	 * @param string $message the message for this exception
	 * @param long $code the code for this exception
	 * @param string $previous if exist a previous exception
	 */
	function __construct($payuCode, $message, $code = NULL, $previous = NULL){
		$this->payUCode = $payuCode;
		parent::__construct($message,$code, $previous);
	}
	
}