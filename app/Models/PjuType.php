<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PjuType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });

        static::updating(function ($type) {
            if ($type->isDirty('name')) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    public function pjuPoints()
    {
        return $this->hasMany(PjuPoint::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
