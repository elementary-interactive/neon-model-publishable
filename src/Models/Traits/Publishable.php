<?php

namespace Neon\Models\Traits;

use Neon\Models\Scopes\PublishedScope;

/** 
 
 * 
 * @author: Balázs Ercsey <balazs.ercsey@elementary-interactive.com>
 */
trait Publishable
{

  /** Boot the publishable trait for a model.
   * 
   * @return void
   */
  public static function bootPublishable()
  {
    static::addGlobalScope(new PublishedScope);
  }

  /** Set published at to a certain time.
   * 
   * @return bool
   */
  public function publishedAt(Carbon\Carbon $time): bool
  {
    return $this->publish($time);
  }

  /**
   * Publish a publishable model instance.
   *
   * @return bool
   */
  public function publish($time = null): bool
  {
    if (is_null($time)) {
      $time = now();
    }

    /** If the publishing event does not return false, we will proceed with this operation.
     */
    if ($this->fireModelEvent('publishing') === false) {
      return false;
    }

    $this->{$this->getPublishedAtColumn()} = $time;

    $result = $this->save();

    $this->fireModelEvent('published', false);

    return $result;
  }

  /** Set expired at to a certain time.
   * 
   * @return bool
   */
  public function expiredAt(Carbon\Carbon $time): bool
  {
    return $this->expire($time);
  }

  /**
   * Expire a publishable model instance.
   *
   * @return bool
   */
  public function expire($time = null): bool
  {
    if (is_null($time)) {
      $time = now();
    }

    /** If the publishing event does not return false, we will proceed with this operation.
     */
    if ($this->fireModelEvent('expiring') === false) {
      return false;
    }

    $this->{$this->getExpiredAtColumn()} = $time;

    $result = $this->save();

    $this->fireModelEvent('expired', false);

    return $result;
  }

  /** Initialize the publishable trait for an instance.
   * 
   * @return void
   */
  public function initializePublishable()
  {
    /** Set published_at field's cast. */
    if (!isset($this->casts[$this->getPublishedAtColumn()])) {
      $this->casts[$this->getPublishedAtColumn()] = 'datetime';
    }

    /** Set expired_at field's cast. */
    if (!isset($this->casts[$this->getExpiredAtColumn()])) {
      $this->casts[$this->getExpiredAtColumn()] = 'datetime';
    }
  }

  /**
   * Register a "publishing" model event callback with the dispatcher.
   *
   * @param  \Closure|string  $callback
   * @return void
   */
  public static function publishing($callback)
  {
    static::registerModelEvent('publishing', $callback);
  }

  /**
   * Register a "published" model event callback with the dispatcher.
   *
   * @param  \Closure|string  $callback
   * @return void
   */
  public static function published($callback)
  {
    static::registerModelEvent('published', $callback);
  }

  /**
   * Register a "expiring" model event callback with the dispatcher.
   *
   * @param  \Closure|string  $callback
   * @return void
   */
  public static function expiring($callback)
  {
    static::registerModelEvent('expiring', $callback);
  }

  /**
   * Register a "expired" model event callback with the dispatcher.
   *
   * @param  \Closure|string  $callback
   * @return void
   */
  public static function expired($callback)
  {
    static::registerModelEvent('expired', $callback);
  }

  /**
   * Get the name of the "published at" column.
   *
   * @return string
   */
  public function getPublishedAtColumn()
  {
    return defined(static::class . '::PUBLISHED_AT') ? static::PUBLISHED_AT : 'published_at';
  }

  /**
   * Get the fully qualified "published at" column.
   *
   * @return string
   */
  public function getQualifiedPublishedAtColumn()
  {
    return $this->qualifyColumn($this->getPublishedAtColumn());
  }

  /**
   * Get the name of the "expired at" column.
   *
   * @return string
   */
  public function getExpiredAtColumn()
  {
    return defined(static::class . '::EXPIRED_AT') ? static::EXPIRED_AT : 'expired_at';
  }

  /**
   * Get the fully qualified "expired at" column.
   *
   * @return string
   */
  public function getQualifiedExpirededAtColumn()
  {
    return $this->qualifyColumn($this->getExpiredAtColumn());
  }
}
