<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
//use  Auth;
use Modules\VanBanDi\Entities\VanBanDi;


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
        //$user = Auth::user();
        //$test = User::role(VAN_THU_HUYEN)->first();

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
