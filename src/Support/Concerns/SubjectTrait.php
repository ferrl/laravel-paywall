<?php

namespace Ferrl\Paywall\Support\Concerns;

use Ferrl\Paywall\Database\Eloquent\Event;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait SubjectContract.
 *
 * @method MorphMany morphMany(string $related)
 */
trait SubjectTrait
{
    /**
     * Represents a database relationship.
     *
     * @return MorphMany
     */
    public function events()
    {
        return $this->morphMany(Event::class);
    }
}
