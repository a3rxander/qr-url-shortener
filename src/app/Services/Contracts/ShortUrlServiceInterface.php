<?php

namespace App\Services\Contracts;

use App\Models\ShortUrl;

interface ShortUrlServiceInterface
{
    public function createShortUrl(string $originalUrl, ?string $customAlias = null): ShortUrl;
    public function getShortUrl(string $identifier): ?ShortUrl;
    public function redirectAndTrack(string $identifier): ?string;
}