<?php

namespace Ferrl\Paywall\Support\Contracts;

use Ferrl\Paywall\Support\Concerns\CauserTrait;
use Ferrl\Paywall\Support\Concerns\SubjectTrait;
use Illuminate\Database\Eloquent\Model;

interface RuleContract
{
    /**
     * Verifies if causer can visualize subject.
     *
     * @param CauserTrait|Model  $causer
     * @param SubjectTrait|Model $subject
     *
     * @return bool
     */
    public function allows($causer, $subject);
}
