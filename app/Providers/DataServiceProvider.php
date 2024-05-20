<?php

namespace App\Providers;

use App\Models\admin\Setting;
use Illuminate\Support\ServiceProvider;

class DataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer([
            'administrator.layouts.main',
            'kasir.layouts.main',
            'administrator.authentication.main',
            'administrator.authentication.login',
            'administrator.authentication.logo',
            'administrator.logs.export',
            
            'member.layouts.main',
            'member.verify.template_mail',
            'administrator.authentication.reset.template_mail'
        ], function ($view) {
            $settings = Setting::get()->toArray();
        
            $settings = array_column($settings, 'value', 'name');
            $view->with('settings', $settings);
        });
    }
}
