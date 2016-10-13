<?php

/**
 *
 * Utility class to process parameters and send reports requests
 *
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 29/10/2013
 *
 */

class PayUReportsRequestUtil extends CommonRequestUtil{
	
	
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
	 * Builds an order details reporting request. The order will be query by id
	 *
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return the request built
	 */
	public static function buildOrderReportingDetails($parameters, $lang=null){
		
		if(!isset($lang)){
			$lang = PayU::$language;
		}
		
		$request = CommonRequestUtil::buildCommonRequest($lang,
				PayUCommands::ORDER_DETAIL);
		
		$orderId = intval(CommonRequestUtil::getParameter($parameters, PayUParameters::ORDER_ID));
		
		
		$request->details = CommonRequestUtil::addMapEntry(null, PayUKeyMapName::ORDER_ID, $orderId); 
		
		return $request;
	}
	
	
	/**
	 * Builds an order details reporting request. The order will be query by reference code
	 *
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return the request built
	 * 
	 */
	public static function buildOrderReportingByReferenceCode($parameters, $lang=null) {
	
		if(!isset($lang)){
			$lang = PayU::$language;
		}
		
		$request = CommonRequestUtil::buildCommonRequest($lang,
				PayUCommands::ORDER_DETAIL_BY_REFERENCE_CODE);
		
		$referenceCode = CommonRequestUtil::getParameter($parameters, PayUParameters::REFERENCE_CODE);
		
		$request->details = CommonRequestUtil::addMapEntry(null, PayUKeyMapName::REFERENCE_CODE, $referenceCode);
	
		return $request;
	}
	

	/**
	 * Builds a transaction reporting request the transaction will be query by id
	 *
	 * @param parameters The parameters to be sent to the server
	 * @param string $lang language of request see SupportedLanguages class
	 * @return The complete reporting request to be sent to the server
	 */
	public static function buildTransactionResponse($parameters, $lang=null) {

		if(!isset($lang)){
			$lang = PayU::$language;
		}
		
		$request = CommonRequestUtil::buildCommonRequest($lang,
				PayUCommands::TRANSACTION_RESPONSE_DETAIL);
		
		$transactionId = CommonRequestUtil::getParameter($parameters, PayUParameters::TRANSACTION_ID);
		
		$request->details = CommonRequestUtil::addMapEntry(null, PayUKeyMapName::TRANSACTION_ID, $transactionId);
	
		return $request;
	}
	
	
}