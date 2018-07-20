<?php

namespace Ferrl\Paywall\Providers;

use Ferrl\Paywall\Database\Eloquent\Causer;
use Ferrl\Paywall\Database\Eloquent\Event;
use Ferrl\Paywall\Database\Eloquent\Subject;
use Ferrl\Paywall\Support\Contracts\AuthorizerContract;
use Ferrl\Paywall\Support\Contracts\RuleContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PaywallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->publishFiles();

        Blade::if('paywall', function ($attributes = []) {
            /**
             * @var Model
             * @var Model              $subject
             * @var AuthorizerContract $authorizer
             * @var RuleContract       $rule
             */
            $causer = data_get($attributes, 'causer') ?: $this->getDefaultCauser();
            $subject = data_get($attributes, 'subject') ?: $this->getDefaultSubject();
            $authorizer = data_get($attributes, 'authorizer') ?: $this->getDefaultAuthorizer();
            $rule = data_get($attributes, 'rule') ?: $this->getDefaultRule();
            $skip = data_get($attributes, 'skip', false);

            $authorized = $authorizer->allows($causer, $subject) ||
                $rule->allows($causer, $subject);

            if ($authorized && !$skip) {
                $this->registerEvent($causer, $subject);

                return true;
            }
        });
    }

    /**
     * Publish package files.
     */
    private function publishFiles()
    {
        $this->publishes([
            __DIR__.'/../../stub/config/paywall.php.stub' => config_path('paywall.php'),
            __DIR__.'/../../stub/migrations/2018_01_01_000000_create_paywall_causers_table.php.stub' => base_path('database/migrations/2018_01_01_000000_create_paywall_causers_table.php'),
            __DIR__.'/../../stub/migrations/2018_01_01_000001_create_paywall_subjects_table.php.stub' => base_path('database/migrations/2018_01_01_000001_create_paywall_subjects_table.php'),
            __DIR__.'/../../stub/migrations/2018_01_01_000002_create_paywall_events_table.php.stub' => base_path('database/migrations/2018_01_01_000002_create_paywall_events_table.php'),
        ], 'paywall');
    }

    /**
     * Get default causer.
     *
     * @return Model
     */
    private function getDefaultCauser()
    {
        if ($user = auth()->user()) {
            return $user;
        }

        return Causer::query()
            ->firstOrCreate([
                'identifier' => request()->ip(),
            ]);
    }

    /**
     * Get default subject.
     *
     * @return Model
     */
    private function getDefaultSubject()
    {
        return Subject::query()
            ->firstOrCreate([
                'identifier' => url()->current(),
            ]);
    }

    /**
     * Register a new event.
     *
     * @param Model $causer
     * @param Model $subject
     *
     * @return Event
     */
    private function registerEvent($causer, $subject)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        return Event::create([
            'causer_id' => $causer->getKey(),
            'causer_type' => get_class($causer),
            'subject_id' => $subject->getKey(),
            'subject_type' => get_class($subject),
        ]);
    }

    /**
     * Get default authorizer.
     *
     * @return AuthorizerContract
     */
    private function getDefaultAuthorizer()
    {
        return app(config('paywall.default_authorizer'));
    }

    /**
     * Get default rule.
     *
     * @return RuleContract
     */
    private function getDefaultRule()
    {
        return app(config('paywall.default_rule'));
    }

    /**
     * Register services.
     */
    public function register()
    {
    }
}
