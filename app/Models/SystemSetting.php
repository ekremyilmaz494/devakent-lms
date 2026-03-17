<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    const CREATED_AT = null;

    protected $fillable = ['key', 'value', 'updated_by'];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value, ?int $userId = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'updated_by' => $userId]
        );
        Cache::forget("setting.{$key}");
    }
}
