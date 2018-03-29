<?php

use Alexo\LaravelPayU\LaravelPayU;
use Carbon\Carbon;
use Fakes\Order;
use Fakes\User;

class LaravelPayUTest extends PHPUnit_Framework_TestCase
{
    protected $approvedOrder;
    public $response;

    public static function setUpBeforeClass()
    {
        if (file_exists(__DIR__.'/../.env')) {
            $dotenv = new Dotenv\Dotenv(__DIR__.'/../');
            $dotenv->load();
        }

        date_default_timezone_set('America/Bogota');
    }

    public function testCreditCardPayment()
    {
        $user = $this->getUser();
        $order = $this->getOrder();
        $now = Carbon::now();
        $dt = $now->addYears(4);

        $session = md5(session_id().microtime());
        $data = [
            \PayUParameters::DESCRIPTION => 'Payment cc test',
            \PayUParameters::IP_ADDRESS => '127.0.0.1',
            \PayUParameters::CURRENCY => 'COP',
            \PayUParameters::CREDIT_CARD_NUMBER => '378282246310005',
            \PayUParameters::CREDIT_CARD_EXPIRATION_DATE => $dt->year.'/02',
            \PayUParameters::CREDIT_CARD_SECURITY_CODE => '1234',
            \PayUParameters::INSTALLMENTS_NUMBER => 1,
            \PayUParameters::DEVICE_SESSION_ID => $session,
            \PayUParameters::PAYMENT_METHOD => 'AMEX',
            \PayUParameters::PAYER_NAME => 'APPROVED',
            \PayUParameters::PAYER_DNI => $user->identification,
            \PayUParameters::REFERENCE_CODE => $order->reference,
            \PayUParameters::USER_AGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
            \PayUParameters::VALUE => $order->value
        ];

        $order->payWith($data, function($response, $order) {
            if ($response->code == 'SUCCESS') {
                // ... check transactionResponse object and do what you need
                $order->update([
                    'payu_order_id' => $response->transactionResponse->orderId,
                    'transaction_id' => $response->transactionResponse->transactionId
                ]);

                $this->assertEquals($response->transactionResponse->state, 'APPROVED');
            } else {
                //... something went wrong
            }
        }, function($error) {
            // ... handle PayUException, InvalidArgument or another error
        });

        return $order;
    }

    public function testCashPayment()
    {
        $user = $this->getUser();
        $order = $this->getOrder();

        // Method only used for testing, because cash payments can't use
        // account testing enviroment equals true
        LaravelPayU::setAccountOnTesting(false);

        $now = Carbon::now();
        $nextWeek = $now->addDays(8);
        $data = [
            \PayUParameters::DESCRIPTION => 'Payment cash test',
            \PayUParameters::IP_ADDRESS => '127.0.0.1',
            \PayUParameters::CURRENCY => 'COP',
            \PayUParameters::EXPIRATION_DATE => $nextWeek->format('Y-m-d\TH:i:s'),
            \PayUParameters::PAYMENT_METHOD => 'BALOTO',
            \PayUParameters::BUYER_EMAIL => 'buyeremail@test.com',
            \PayUParameters::PAYER_NAME => 'APPROVED',
            \PayUParameters::PAYER_DNI => $user->identification,
            \PayUParameters::REFERENCE_CODE => $order->reference,
            \PayUParameters::VALUE => $order->value
        ];

        $order->payWith($data, function($response) {
            if ($response->code == 'SUCCESS') {
                // ... check transactionResponse object and do what you need
                $this->assertEquals($response->transactionResponse->state, 'PENDING');
            } else {
                //... something went wrong
            }
        }, function($error) {
            // ... handle PayUException, InvalidArgument or another error
        });
    }

    public function testPSEPayment()
    {
        // Get PSE banks first, typically sent with the form
        // that is filled by the payer
        LaravelPayU::getPSEBanks(function($banks) {
            $bankCode = 0;

            foreach($banks as $bank) {
                if ($bank->description == 'Banco Union Colombiano') {
                    $bankCode = $bank->pseCode;
                }
            }

            $user = $this->getUser();
            $order = $this->getOrder();

            // Method only used for testing, because PSE payments can't use
            // account testing enviroment equals true
            LaravelPayU::setAccountOnTesting(false);

            $session = md5(session_id().microtime());
            $data = [
                \PayUParameters::DESCRIPTION => 'Payment pse test',
                \PayUParameters::IP_ADDRESS => '127.0.0.1',
                \PayUParameters::CURRENCY => 'COP',
                \PayUParameters::PAYER_COOKIE => 'pt1t38347bs6jc9ruv2ecpv7o2',
                \PayUParameters::PAYMENT_METHOD => 'PSE',
                \PayUParameters::BUYER_EMAIL => $user->email,
                \PayUParameters::PAYER_NAME => $user->name,
                \PayUParameters::PAYER_EMAIL => $user->email,
                \PayUParameters::PAYER_DNI => $user->identification,
                \PayUParameters::PAYER_CONTACT_PHONE=> '7563126',
                \PayUParameters::PAYER_DOCUMENT_TYPE => 'CC',
                \PayUParameters::PAYER_PERSON_TYPE => 'N',
                \PayUParameters::PSE_FINANCIAL_INSTITUTION_CODE => $bankCode,
                \PayUParameters::REFERENCE_CODE => $order->reference,
                \PayUParameters::DEVICE_SESSION_ID => $session,
                \PayUParameters::USER_AGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
                \PayUParameters::VALUE => $order->value
            ];

            $order->payWith($data, function($response) {
                if ($response->code == 'SUCCESS') {
                    // ... check transactionResponse object and do what you need
                    $this->assertEquals($response->transactionResponse->state, 'PENDING');
                } else {
                    //... something went wrong
                }
            }, function($error) {
                // ... handle PayUException, InvalidArgument or another error
            });

        }, function($error) {
            // ... handle PayUException, InvalidArgument or another error
        });
    }

    /**
     * @depends testCreditCardPayment
     */
    public function testSearchOrderById($order)
    {
        $order->searchById(function($response) {
            // ... check response and use the order data to update or something
            $this->assertEquals($response->status, 'CAPTURED');
        }, function($error) {
            // ... handle PayUException, InvalidArgument or another error
        });
    }

    /**
     * @depends testCreditCardPayment
     */
    public function testSearchOrderByReference($order)
    {
        $order->searchByReference(function($response) {
            // ... check response array list and use the order data to update or something
            $this->assertEquals($response[0]->status, 'CAPTURED');
        }, function($error) {
            // ... handle PayUException, InvalidArgument or another error
        });
    }

    /**
     * @depends testCreditCardPayment
     */
    public function testSearchTransactionResponse($order)
    {
        $order->searchByTransaction(function($response) {
            // ... check response array list and use the order data to update or something
            $this->assertEquals($response->state, 'APPROVED');
        }, function($error) {
            // ... handle PayUException, InvalidArgument or another error
        });
    }

    private function getUser()
    {
        return new User([
            'name' => 'Taylor Otwell',
            'email' => 'user@tests.com',
            'identification' => '1000100100'
        ]);
    }

    private function getOrder()
    {
        return new Order([
            'payu_order_id' => null,
            'transaction_id' => null,
            'reference' => uniqid(time()),
            'value' => 20000,
            'user_id' => 1
        ]);
    }
}
