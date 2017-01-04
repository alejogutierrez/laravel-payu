<?php

namespace Alexo\LaravelPayU;

use InvalidArgumentException;
use Alexo\LaravelPayU\LaravelPayU;

trait Payable
{
    /**
     * Make a "one off" payment on the given order.
     *
     * @param  array  $params
     * @param  callback  $onSuccess
     * @param  callback  $onError
     * @return mixed
     *
     */
    public function payWith(array $params = [], $onSuccess, $onError)
    {
        LaravelPayU::setPayUEnvironment();

        try {
            $params[\PayUParameters::ACCOUNT_ID] = LaravelPayU::getAccountId();
            $params[\PayUParameters::COUNTRY] = LaravelPayU::getCountry();

            if (array_key_exists(\PayUParameters::PAYMENT_METHOD, $params)) {
                if ($params[\PayUParameters::PAYMENT_METHOD] == 'PSE') {
                    if (!array_key_exists(\PayUParameters::RESPONSE_URL, $params)) {
                        $params[\PayUParameters::RESPONSE_URL] = LaravelPayU::getRedirectPSE();
                    }
                }
            }

            $response = \PayUPayments::doAuthorizationAndCapture($params);

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
     * Check authorization for payment on the given order.
     *
     * @param  array  $params
     * @param  callback  $onSuccess
     * @param  callback  $onError
     * @return mixed
     *
     */
    public function authorizeWith(array $params = [], $onSuccess, $onError)
    {
        LaravelPayU::setPayUEnvironment();

        try {
            $params[\PayUParameters::ACCOUNT_ID] = LaravelPayU::getAccountId();
            $params[\PayUParameters::COUNTRY] = LaravelPayU::getCountry();

            $response = \PayUPayments::doAuthorization($params);

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
     * Make capture to the given order.
     *
     * @param  array  $params
     * @param  callback  $onSuccess
     * @param  callback  $onError
     * @return mixed
     *
     */
    public function captureWith(array $params = [], $onSuccess, $onError)
    {
        LaravelPayU::setPayUEnvironment();

        try {
            $params[\PayUParameters::ACCOUNT_ID] = LaravelPayU::getAccountId();
            $params[\PayUParameters::COUNTRY] = LaravelPayU::getCountry();

            $response = \PayUPayments::doCapture($params);

            if ($response != null) {
                $onSuccess($response, $this);
            }
        } catch (\PayUException $exc) {
            $onError($exc);
        } catch (InvalidArgumentException $exc) {
            $onError($exc);
        }
    }
}
