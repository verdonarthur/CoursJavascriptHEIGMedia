<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WebAvServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
	    $this->app->singleton('App/Services/Normalize', function($app) {
                return new \App\Services\Normalize($app['config']['truc']['local']);
            });
	}

}
