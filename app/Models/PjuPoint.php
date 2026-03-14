<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PjuPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'category_id',
        'pju_type_id',
        'daya',
        'letak',
        'type',
        'lat',
        'long',
        'kecamatan_id',
        'desa_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pjuType()
    {
        return $this->belongsTo(PjuType::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'normal'
            ? '<span class="badge-normal">Normal</span>'
            : '<span class="badge-mati">Mati</span>';
    }
}
