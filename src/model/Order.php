<?php

namespace App;

use Alexo\LaravelPayU\Payable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Payable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference', 'state', 'value', 'user_id'
    ];
}
