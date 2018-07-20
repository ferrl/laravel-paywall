<?php

namespace Ferrl\Paywall\Support\Rules;

use Carbon\Carbon;
use Ferrl\Paywall\Database\Eloquent\Event;
use Ferrl\Paywall\Support\Concerns\CauserTrait;
use Ferrl\Paywall\Support\Concerns\SubjectTrait;
use Ferrl\Paywall\Support\Concerns\RuleTrait;
use Ferrl\Paywall\Support\Contracts\RuleContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaxEventsInDateRange implements RuleContract
{
    use RuleTrait;

    /**
     * Verifies if causer can visualize subject.
     *
     * @param CauserTrait|Model  $causer
     * @param SubjectTrait|Model $subject
     *
     * @return bool
     */
    public function allows($causer, $subject)
    {
        $maxEvents = $this->configuration('max_events');
        $totalEvents = $this->totalEvents($causer, $subject);

        return $totalEvents < $maxEvents;
    }

    /**
     * Converts time range string to Carbon.
     *
     * @param string|int $timeRange
     *
     * @return Carbon
     */
    private function timeRangeToDate($timeRange)
    {
        switch ($timeRange) {
            case 'today':
                return now()->startOfDay();

            default:
                return now()->subDays($timeRange);
        }
    }

    /**
     * Get total of events.
     *
     * @param Model $causer
     * @param Model $subject
     *
     * @return array
     */
    private function totalEvents($causer, $subject)
    {
        $timeRange = $this->configuration('time_range');
        $ignoreSelf = $this->configuration('ignore_self');

        $totalEvents = Event::query()
            ->select('subject_id', DB::raw('count(*)'))
            ->when($ignoreSelf, function ($query) use ($subject) {
                /* @var Builder $query */
                $query->where(function ($query) use ($subject) {
                    /* @var Builder $query */
                    $query->where('subject_type', '!=', get_class($subject))
                        ->orWhere('subject_id', '!=', $subject->getKey());
                });
            })
            ->where('causer_type', get_class($causer))
            ->where('causer_id', $causer->getKey())
            ->where('created_at', '>=', $this->timeRangeToDate($timeRange))
            ->groupBy('subject_id')
            ->get()
            ->count();

        return $totalEvents;
    }
}
