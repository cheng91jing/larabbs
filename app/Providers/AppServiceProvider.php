<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
	    //注册模型观察者
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);

        //操作时间DateTime扩展 本地化
        Carbon::setLocale('zh');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
