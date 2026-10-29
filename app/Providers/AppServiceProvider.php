<?php

namespace App\Providers;

use App\Listeners\LogAuthActivity;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        App::setLocale('id');
        Carbon::setLocale('id');

        Blade::directive('tgl', fn ($expression) => "<?php echo \\App\\Support\\Format::date({$expression}); ?>");
        Blade::directive('tglwaktu', fn ($expression) => "<?php echo \\App\\Support\\Format::date({$expression}, 'j M Y H:i'); ?>");
        Blade::directive('num', fn ($expression) => "<?php echo \\App\\Support\\Format::number({$expression}); ?>");

        Event::listen(Login::class, [LogAuthActivity::class, 'handleLogin']);
        Event::listen(Logout::class, [LogAuthActivity::class, 'handleLogout']);
        Event::listen(Registered::class, [LogAuthActivity::class, 'handleRegistered']);
    }
}