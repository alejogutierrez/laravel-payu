<?php

namespace Fakes;

use Alexo\LaravelPayU\Payable;
use Alexo\LaravelPayU\Searchable;

class Order extends FakeObject
{
    use Payable, Searchable;

    public $payu_order_id = null;
    public $transaction_id = null;
    public $state = 1;
    public $reference = '';
    public $value = null;
    protected $user_id = null;

    public function update($values)
    {
        $this->updateValues($values);
    }
}
