<?php

namespace Ferrl\Paywall\Support\Concerns;

trait RuleTrait
{
    /**
     * Retrieves configuration for rule.
     *
     * @param string $key
     *
     * @return string|array
     */
    protected function configuration($key = null)
    {
        if (is_null($key)) {
            return config('paywall.rules.'.snake_case(class_basename($this)));
        }

        return config('paywall.rules.'.snake_case(class_basename($this)).'.'.$key);
    }

    /**
     * Verifies if causer can't visualize entry.
     *
     * @return bool
     */
    public function denies()
    {
        return call_user_func_array([$this, 'allows'], func_get_args());
    }
}
