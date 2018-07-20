<?php

namespace Ferrl\Paywall\Database\Eloquent;

use Ferrl\Paywall\Support\Concerns\SubjectTrait;

class Subject extends Model
{
    use SubjectTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['identifier'];
}
