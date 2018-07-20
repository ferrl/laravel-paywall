<?php

namespace Ferrl\Paywall\Database\Eloquent;

use Ferrl\Paywall\Support\Concerns\CauserTrait;
use Ferrl\Paywall\Support\Concerns\SubjectTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Event.
 *
 * @property CauserTrait|\Illuminate\Database\Eloquent\Model causer
 * @property SubjectTrait|\Illuminate\Database\Eloquent\Model subject
 */
class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['causer_id', 'causer_type', 'subject_id', 'subject_type'];

    /**
     * Represents a database relationship.
     *
     * @return MorphTo
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Represents a database relationship.
     *
     * @return MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
