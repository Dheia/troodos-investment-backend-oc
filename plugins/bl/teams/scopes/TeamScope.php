<?php

namespace BL\Teams\Scopes;

use Backend\Facades\BackendAuth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TeamScope implements Scope
{
  /**
   * Apply the scope to a given Eloquent query builder.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $builder
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @return void
   */
  public function apply(Builder $builder, Model $model)
  {
    if (BackendAuth::check()) {
      $builder->where('team_id', '=', BackendAuth::getUser()->team_id);
    }
  }
}
