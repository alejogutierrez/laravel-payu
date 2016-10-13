<?php
/**
 * Contains the response codes for a transaction in the PayU SDK.
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */
class PayUTransactionResponseCode {

	/** Error transaction code */
	const ERROR = 'ERROR';
	/** Approved transaction code */
	const APPROVED = 'APPROVED';
	/** Transaction declined by the entity code */
	const ENTITY_DECLINED = 'ENTITY_DECLINED';
	/** Transaction rejected by anti fraud system code */
	const ANTIFRAUD_REJECTED = 'ANTIFRAUD_REJECTED';
	/** Transaction review pending code */
	const PENDING_TRANSACTION_REVIEW = 'PENDING_TRANSACTION_REVIEW';
	/** Transaction expired code */
	const EXPIRED_TRANSACTION = 'EXPIRED_TRANSACTION';
	/** The payment provider had an internal error code */
	const INTERNAL_PAYMENT_PROVIDER_ERROR = 'INTERNAL_PAYMENT_PROVIDER_ERROR';
	/** The payment provider is not active code */
	const INACTIVE_PAYMENT_PROVIDER = 'INACTIVE_PAYMENT_PROVIDER';
	/** The digital certificate could not be found code */
	const DIGITAL_CERTIFICATE_NOT_FOUND = 'DIGITAL_CERTIFICATE_NOT_FOUND';
	/** Transaction rejected by payment network */
	const PAYMENT_NETWORK_REJECTED = 'PAYMENT_NETWORK_REJECTED';
	/** Invalid data code */
	const INVALID_EXPIRATION_DATE_OR_SECURITY_CODE = 'INVALID_EXPIRATION_DATE_OR_SECURITY_CODE';
	/** Insufficient funds code */
	const INSUFFICIENT_FUNDS = 'INSUFFICIENT_FUNDS';
	/** Credit card not authorized code */
	const CREDIT_CARD_NOT_AUTHORIZED_FOR_INTERNET_TRANSACTIONS = 'CREDIT_CARD_NOT_AUTHORIZED_FOR_INTERNET_TRANSACTIONS';
	/** Transaction is not valid code */
	const INVALID_TRANSACTION = 'INVALID_TRANSACTION';
	/** Credit card is not valid code */
	const INVALID_CARD = 'INVALID_CARD';
	/** Credit card expired code */
	const EXPIRED_CARD = 'EXPIRED_CARD';
	/** Credit card is restricted code */
	const RESTRICTED_CARD = 'RESTRICTED_CARD';
	/** Need to contact the entity code */
	const CONTACT_THE_ENTITY = 'CONTACT_THE_ENTITY';
	/** Need to repeat transaction code */
	const REPEAT_TRANSACTION = 'REPEAT_TRANSACTION';
	/** Entity sent an error message code */
	const ENTITY_MESSAGING_ERROR = 'ENTITY_MESSAGING_ERROR';
	/** Transaction confirmation is pending code */
	const PENDING_TRANSACTION_CONFIRMATION = 'PENDING_TRANSACTION_CONFIRMATION';
	/** Bank could not be reached code */
	const BANK_UNREACHABLE = 'BANK_UNREACHABLE';
	/** Amount not valid code */
	const EXCEEDED_AMOUNT = 'EXCEEDED_AMOUNT';
	/** Transaction not accepted code */
	const NOT_ACCEPTED_TRANSACTION = 'NOT_ACCEPTED_TRANSACTION';
	/** Transaction amounts could not be converted code */
	const ERROR_CONVERTING_TRANSACTION_AMOUNTS = 'ERROR_CONVERTING_TRANSACTION_AMOUNTS';
	/** Transaction transmission is pending code */
	const PENDING_TRANSACTION_TRANSMISSION = 'PENDING_TRANSACTION_TRANSMISSION';
	/** Bad response from the payment network code */
	const PAYMENT_NETWORK_BAD_RESPONSE = 'PAYMENT_NETWORK_BAD_RESPONSE';
	/** Connection failure with the payment network code */
	const PAYMENT_NETWORK_NO_CONNECTION = 'PAYMENT_NETWORK_NO_CONNECTION';
	/** Payment network not sending response code */
	const PAYMENT_NETWORK_NO_RESPONSE = 'PAYMENT_NETWORK_NO_RESPONSE';
	
	//Clinic
	/** Fix was not required code */
	const FIX_NOT_REQUIRED = 'FIX_NOT_REQUIRED';
	/** Transaction was automatically fixed  and could make a reversal code */
	const AUTOMATICALLY_FIXED_AND_SUCCESS_REVERSAL = 'AUTOMATICALLY_FIXED_AND_SUCCESS_REVERSAL';
	/** Transaction was automatically fixed  and couldn't make a reversal code */
	const AUTOMATICALLY_FIXED_AND_UNSUCCESS_REVERSAL = 'AUTOMATICALLY_FIXED_AND_UNSUCCESS_REVERSAL';
	/** Transaction can't be automatically fixed code */
	const AUTOMATIC_FIXED_NOT_SUPPORTED = 'AUTOMATIC_FIXED_NOT_SUPPORTED';
	/** Transaction was not fixed due to an error state code */
	const NOT_FIXED_FOR_ERROR_STATE = 'NOT_FIXED_FOR_ERROR_STATE';
	/** Transaction could not be fixed and reversed code */
	const ERROR_FIXING_AND_REVERSING = 'ERROR_FIXING_AND_REVERSING';
	/** Transaction was not fixed due to incomplete data code */
	const ERROR_FIXING_INCOMPLETE_DATA = 'ERROR_FIXING_INCOMPLETE_DATA';

	/** Cancelation review pending code */
	const PENDING_CANCELATION_REVIEW = 'PENDING';
}
