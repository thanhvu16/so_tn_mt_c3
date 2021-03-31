<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use  Auth;

class MailConfigServiceProvider extends ServiceProvider
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

/*            $config = array(
                'driver'     => env('MAIL_DRIVER'),
                'host'       => env('MAIL_HOST'),
                'port'       => env('MAIL_PORT'),
                'username'   => Auth::user()->email,
                'password'   => Auth::user()->password_email,
                'encryption' => null,
                'from'       => array('address' => Auth::user()->email, 'name' => env('MAIL_FROM_NAME')),
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );

            Config::set('mail', $config);*/


    }
}
