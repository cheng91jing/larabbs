<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);
		\App\Models\Link::observe(\App\Observers\LinkObserver::class);

        //操作时间DateTime扩展 本地化
        Carbon::setLocale('zh');

        \Horizon::auth(function ($request){
            return \Auth::user()->hasRole('Founder');
        });

        //设置 Dingo API 格式化返回数据时使用的 Fractal 的 Serializer
        app(\Dingo\Api\Transformer\Factory::class)->setAdapter(function ($app){
            $fractal = new \League\Fractal\Manager;

            $fractal->setSerializer(new \League\Fractal\Serializer\ArraySerializer);

            return new \Dingo\Api\Transformer\Adapter\Fractal($fractal);
        });

        //Passport 路由
        Passport::routes();
        // access_token 过期时间
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        // refresh Tokens 过期时间
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(app()->isLocal()){
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }

        //为API 手动处理异常  【DingoApi 默认所有异常都会返回 500】
        \API::error(function(\Illuminate\Database\Eloquent\ModelNotFoundException $exception){
            abort(404);
        });
        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });

    }
}
