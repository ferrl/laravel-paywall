<?php

namespace Ferrl\Paywall\Support\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AuthorizerContract
{
    /**
     * Verifies if user can override rules.
     *
     * @param Model $causer
     * @param Model $subject
     *
     * @return bool
     */
    public function allows($causer, $subject);
}
