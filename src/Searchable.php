<?php

namespace Alexo\LaravelPayU;

use Exception;

trait Searchable
{
    /**
     * Search an order using the id asigned by PayU.
     *
     * @param  callback  $onSuccess
     * @param  callback  $onError
     * @return mixed
     *
     */
    public function searchById($onSuccess, $onError)
    {
        LaravelPayU::setPayUEnvironment();

        try {
            $params[\PayUParameters::ORDER_ID] = $this->payu_order_id;

            $response = \PayUReports::getOrderDetail($params);

            if ($response) {
                $onSuccess($response, $this);
            }
        } catch (\PayUException $exc) {
            $onError($exc);
        } catch (InvalidArgumentException $exc) {
            $onError($exc);
        }
    }

    /**
     * Search an order using the reference created before attempt the processing.
     *
     * @param  callback  $onSuccess
     * @param  callback  $onError
     * @return mixed
     *
     */
    public function searchByReference($onSuccess, $onError)
    {
        LaravelPayU::setPayUEnvironment();

        try {
            $params[\PayUParameters::REFERENCE_CODE] = $this->reference;

            $response = \PayUReports::getOrderDetailByReferenceCode($params);

            if ($response) {
                $onSuccess($response, $this);
            }
        } catch (\PayUException $exc) {
            $onError($exc);
        } catch (InvalidArgumentException $exc) {
            $onError($exc);
        }
    }

    /**
     * Search an order using the transactionId asigned by PayU.
     *
     * @param  callback  $onSuccess
     * @param  callback  $onError
     * @return mixed
     *
     */
    public function searchByTransaction($onSuccess, $onError)
    {
        LaravelPayU::setPayUEnvironment();

        try {
            $params[\PayUParameters::TRANSACTION_ID] = $this->transaction_id;

            $response = \PayUReports::getTransactionResponse($params);

            if ($response) {
                $onSuccess($response, $this);
            }
        } catch (\PayUException $exc) {
            $onError($exc);
        } catch (InvalidArgumentException $exc) {
            $onError($exc);
        }
    }
}
