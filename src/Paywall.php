<?php

namespace Ferrl\Paywall;

use Ferrl\Paywall\Database\Eloquent\Causer;
use Ferrl\Paywall\Database\Eloquent\Event;
use Ferrl\Paywall\Database\Eloquent\Subject;
use Ferrl\Paywall\Support\Contracts\AuthorizerContract;
use Ferrl\Paywall\Support\Contracts\RuleContract;
use Illuminate\Database\Eloquent\Model;

class Paywall
{
    /**
     * Handle paywall rules.
     *
     * @param array $attributes
     *
     * @return bool
     */
    public function allows($attributes = [])
    {
        /**
         * @var Model              $causer
         * @var Model              $subject
         * @var AuthorizerContract $authorizer
         * @var RuleContract       $rule
         */
        $causer = $this->getCauser(data_get($attributes, 'causer'));
        $subject = $this->getSubject(data_get($attributes, 'subject'));
        $authorizer = $this->getAuthorizer(data_get($attributes, 'authorizer'));
        $rule = $this->getRule(data_get($attributes, 'rule'));

        $skip = data_get($attributes, 'skip', false);

        $authorized = $authorizer->allows($causer, $subject) ||
            $rule->allows($causer, $subject);

        if ($authorized) {
            if (!$skip) {
                $this->registerEvent($causer, $subject);
            }

            return true;
        }

        return false;
    }

    /**
     * Is allowed to access content?
     *
     * @param array $attributes
     *
     * @return bool
     */
    public function isAllowed($attributes = [])
    {
        return $this->allows($attributes + ['skip' => true]);
    }

    /**
     * Get default causer.
     *
     * @param Model|null $causer
     *
     * @return Model
     */
    private function getCauser($causer)
    {
        if ($causer || $causer = auth()->user()) {
            return $causer;
        }

        return Causer::query()
            ->firstOrCreate([
                'identifier' => request()->ip(),
            ]);
    }

    /**
     * Get default subject.
     *
     * @param Model|string|null $subject
     *
     * @return Model
     */
    private function getSubject($subject)
    {
        if ($subject instanceof Model) {
            return $subject;
        }

        $identifier = $subject ?: url()->current();

        return Subject::query()
            ->firstOrCreate(compact('identifier'));
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
     * @param AuthorizerContract|null $authorizer
     *
     * @return AuthorizerContract
     */
    private function getAuthorizer($authorizer)
    {
        return $authorizer ?: app(config('paywall.default_authorizer'));
    }

    /**
     * Get default rule.
     *
     * @param RuleContract|null $rule
     *
     * @return RuleContract
     */
    private function getRule($rule)
    {
        return $rule ?: app(config('paywall.default_rule'));
    }
}
