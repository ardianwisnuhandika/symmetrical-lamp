<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function desas()
    {
        return $this->hasMany(Desa::class);
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
