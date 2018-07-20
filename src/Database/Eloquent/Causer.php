<?php

namespace Ferrl\Paywall\Database\Eloquent;

use Ferrl\Paywall\Support\Concerns\CauserTrait;

class Causer extends Model
{
    use CauserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['identifier'];
}
