<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    /** @use HasFactory<\Database\Factories\ShortUrlFactory> */
    use HasFactory;

    
    protected $fillable = [
        'code',
        'original_url',
        'custom_alias',
        'is_active',
        'click_count',
        'last_accessed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'click_count' => 'integer',
        'last_accessed_at' => 'datetime',
    ];

    public function getShortUrlAttribute(): string
    {
        $baseUrl = config('app.url');
        $identifier = $this->custom_alias ?: $this->code;
        return "{$baseUrl}/s/{$identifier}";
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
        $this->update(['last_accessed_at' => now()]);
    }
}