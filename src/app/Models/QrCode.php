<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    /** @use HasFactory<\Database\Factories\QrCodeFactory> */
    use HasFactory;

    protected $fillable = [
        'identifier',
        'content',
        'type',
        'format',
        'size',
        'file_path',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        return config('app.url') . '/api/qr/' . $this->identifier . '/image';
    }

    public function getFullFilePathAttribute(): string
    {
        return storage_path('app/' . $this->file_path);
    }
}