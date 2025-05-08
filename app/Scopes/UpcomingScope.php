<?php

namespace App\Scopes;

use Statamic\Query\Scopes\Scope;
use Carbon\Carbon;

class UpcomingScope extends Scope
{
  /**
   * Apply the scope.
   *
   * @param \Statamic\Query\Builder $query
   * @param array $values
   * @return void
   */
  public function apply($query, $values)
  {
    // $todayFormatted = Carbon::today()->format('F jS, Y'); // e.g. "May 7th, 2025"
    // return $query->where('event_date', '>=', $todayFormatted);
    return $query->where('event_date', '>=', Carbon::today()->toDateString());
  }
}

