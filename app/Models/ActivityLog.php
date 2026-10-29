<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public static function record(string $action, string $description, ?int $userId = null): ?self
    {
        $userId ??= Auth::id();

        if (! $userId) {
            return null;
        }

        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
        ]);
    }
}