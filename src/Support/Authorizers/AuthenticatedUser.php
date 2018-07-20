<?php

namespace Ferrl\Paywall\Support\Authorizers;

use Ferrl\Paywall\Support\Contracts\AuthorizerContract;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

class AuthenticatedUser implements AuthorizerContract
{
    /**
     * Verifies if user can override rules.
     *
     * @param Model $causer
     * @param Model $subject
     *
     * @return bool
     */
    public function allows($causer, $subject)
    {
        return $causer instanceof Authorizable;
    }
}
