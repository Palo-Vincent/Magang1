<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;

class LogAuthActivity
{
    public function handleLogin(Login $event): void
    {
        ActivityLog::record('login', $event->user->name.' masuk ke sistem', $event->user->id);
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            ActivityLog::record('logout', $event->user->name.' keluar dari sistem', $event->user->id);
        }
    }

    public function handleRegistered(Registered $event): void
    {
        ActivityLog::record('register', 'Akun baru terdaftar: '.$event->user->name, $event->user->id);
    }
}