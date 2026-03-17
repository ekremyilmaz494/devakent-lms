<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'color'];

    protected static function booted(): void
    {
        $clear = fn () => Cache::forget('categories.all');
        static::saved($clear);
        static::deleted($clear);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
