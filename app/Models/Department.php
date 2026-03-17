<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'description', 'is_active'];

    protected static function booted(): void
    {
        $clear = fn () => Cache::forget('departments.all');
        static::saved($clear);
        static::deleted($clear);
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activeUsers(): HasMany
    {
        return $this->hasMany(User::class)->where('is_active', true);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_departments')->withPivot('created_at');
    }
}
