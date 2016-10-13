<?php
/**
 * This class contains the payu commands
 * availables in payu platform
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */
class PayUCommands {

	/** only verify if the service available*/
	const PING = "PING";

	/** send a transaction to be processed */
	const SUBMIT_TRANSACTION = "SUBMIT_TRANSACTION";

	/** Returns the active payment methods for a merchant. */
	const GET_PAYMENT_METHODS = "GET_PAYMENT_METHODS";

	/** Returns the banks list available */
	const GET_BANKS_LIST = "GET_BANKS_LIST";

	/** Create token */
	const CREATE_TOKEN = "CREATE_TOKEN";
	
	/** Remove  token */
	const REMOVE_TOKEN = "REMOVE_TOKEN";
	
	/** Create batch tokens */
	const CREATE_BATCH_TOKENS = "CREATE_BATCH_TOKENS";
	
	/** Process the batch of transactions with tokens */
	const PROCESS_BATCH_TRANSACTIONS_TOKEN = "PROCESS_BATCH_TRANSACTIONS_TOKEN";

	/** Return a order detail */
	const ORDER_DETAIL = "ORDER_DETAIL";
	
	/** Return a transaction response detail */
	const TRANSACTION_RESPONSE_DETAIL = "TRANSACTION_RESPONSE_DETAIL";
	
	/** Return a transaction response detail by reference code */
	const ORDER_DETAIL_BY_REFERENCE_CODE = "ORDER_DETAIL_BY_REFERENCE_CODE";
	
	/** Search batch credit card token */
	const BATCH_CREDIT_CARD_TOKEN = "BATCH_CREDIT_CARD_TOKEN";

	/** Find a token by payer */
	const GET_TOKENS = "GET_TOKENS";
	
	/** Evaluate if a payment method is available in Payments API */
	const GET_PAYMENT_METHOD_AVAILABILITY = "GET_PAYMENT_METHOD_AVAILABILITY";

}