<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Config;
use DB;
use App\User;
use Auth;
use Illuminate\Support\Facades\Artisan;
use Session;

class checkdatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Config::get('database.connections.sqlsrv.host') != '127.0.0.1') {
            if (Session::has('tenDB')) {
                Config::set('database.connections.sqlsrv', array(
                    'driver' => 'sqlsrv',
                    'host' => env('DB_HOST', '200.200.200.154'),
                    'port' => env('DB_PORT', '1433'),
                    'database' => Session::get('tenDB'),
                    'username' => env('DB_USERNAME', 'sa'),
                    'password' => env('DB_PASSWORD', 'Sql@tnmt2021'),
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => '',
                ));
                Artisan::call('config:clear');
                DB::reconnect('sqlsrv');
            }
        }
        return $next($request);
    }
}
