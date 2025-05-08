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
    return $query->where('event_date', '>=', Carbon::today()->format('Y-m-d'));
  }
}

