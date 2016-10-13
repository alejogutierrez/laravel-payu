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
}
