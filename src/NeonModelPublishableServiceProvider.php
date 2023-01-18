<?php

namespace Neon;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Http\Kernel;
use Neon\View\Components\Menu;


class NeonModelPublishableServiceProvider extends ServiceProvider
{

  /** Bootstrap any application services.
   *
   * @param \Illuminate\Contracts\Http\Kernel  $kernel
   *
   * @return void
   */
  public function boot(Kernel $kernel): void
  {
    
  }
}
