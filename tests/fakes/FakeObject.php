<?php

namespace Fakes;

class FakeObject
{
    public function __construct(array $values)
    {
        $this->updateValues($values);
    }

    protected function updateValues($values)
    {
        foreach($values as $key => $value) {
            $this->$key = $value;
        }
    }
}
