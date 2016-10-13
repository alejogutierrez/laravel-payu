<?php

/**
 * Contains information about the configuration 
 * for this client api
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */
class PayUConfig{
	
	/** the payu date format */
	const PAYU_DATE_FORMAT = 'Y-m-d\TH:i:s'; //DateTime::ISO8601
	
	/** the payu credit card secondary date format */
	const PAYU_SECONDARY_DATE_FORMAT = 'Y/m';
	
	/** the payu birhday format */
	const PAYU_DAY_FORMAT = 'Y-m-d';
	
	/** 
	 * if remove null values over object sent in a request api
	 * the null values over response will be removed too
	 */
	const REMOVE_NULL_OVER_REQUEST = TRUE;

}