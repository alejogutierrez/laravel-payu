<?php

namespace Alexo\LaravelPayU;

class PayUVariables
{
    public static function getAvailableCreditCards()
    {
        return [
            'NARANJA',
            'SHOPPING',
            'CENCOSUD',
            'ARGENCARD',
            'CABAL',
            'VISA',
            'AMEX',
            'MASTERCARD',
            'DINERS',
            'CODENSA',
            'ELO'
        ];
    }
}
