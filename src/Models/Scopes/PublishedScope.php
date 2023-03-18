<?php

namespace Neon\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Model;

class PublishedScope implements Scope
{
  /**
   * All of the extensions to be added to the builder.
   *
   * @var string[]
   */
  protected $extensions = ['WithNotPublished'];

  /**
   * Extend the query builder with the needed functions.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $builder
   * @return void
   */
  public function extend(Builder $builder)
  {
    foreach ($this->extensions as $extension) {
      $this->{"add{$extension}"}($builder);
    }
  }



  /**
   * Apply the scope to a given Eloquent query builder.
   *
   * @param  \Illuminate\Database\Eloquent\Builder $builder
   * @param  \Illuminate\Database\Eloquent\Model $model
   * @return void
   */
  public function apply(Builder $builder, Model $model)
  {
    $builder
      ->where($model->getQualifiedPublishedAtColumn(), '<=', now())
      ->where(
        function ($sub_query) use ($model) {
          $sub_query
            ->whereNull($model->getQualifiedExpiredAtColumn())
            ->orWhere($model->getQualifiedExpiredAtColumn(), '>', now());
        }
      );
  }

  /**
   * Add the with-not-published extension to the builder.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $builder
   * @return void
   */
  protected function addWithNotPublished(Builder $builder)
  {
    $builder->macro('withNotPublished', function (Builder $builder) {
      return $builder->withoutGlobalScope($this);
    });
  }
}
