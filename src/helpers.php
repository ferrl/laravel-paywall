<?php

if (!function_exists('paywall')) {
    /**
     * Get paywall singleton.
     *
     * @return \Ferrl\Paywall\Paywall
     */
    function paywall()
    {
        return app('paywall');
    }
}

if (!function_exists('paywallAllows')) {
    /**
     * Paywall grants access.
     *
     * @param array $attributes
     *
     * @return bool
     */
    function paywallAllows($attributes = [])
    {
        return app('paywall')->allows($attributes);
    }
}
