<?php

/**
 * Specifies the transaction operations 
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 * 
 */
class TransactionType
{
	
	/** Only authorization transaction. */
	const AUTHORIZATION = "AUTHORIZATION";
	 
	/** Authorization and capture transaction. */
	const AUTHORIZATION_AND_CAPTURE = "AUTHORIZATION_AND_CAPTURE";
	
	/** Only capture transaction. */
	const CAPTURE = "CAPTURE";
	
	/** Cancel transaction. */
	const CANCELLATION = "CANCELLATION";
	
	/** Void transaction. */
	const VOID = "VOID";
	
	/** Refund transaction. */
	const REFUND = "REFUND";
	
	/** Credit transaction. */
	const CREDIT = "CREDIT";

}