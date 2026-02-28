<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PjuPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori',
        'jenis',
        'daya',
        'letak',
        'type',
        'lat',
        'long',
        'status',
        'is_verified',
        'created_by',
        'verified_by',
        'verified_at',
        'foto',
        'keterangan',
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'long' => 'decimal:8',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'normal'
            ? '<span class="badge-normal">Normal</span>'
            : '<span class="badge-mati">Mati</span>';
    }
}
