<?php

namespace App\Repositories\Contracts;

use App\Models\ShortUrl;

interface ShortUrlRepositoryInterface
{
    public function create(array $data): ShortUrl;
    public function findByCode(string $code): ?ShortUrl;
    public function findByCustomAlias(string $alias): ?ShortUrl;
    public function findByIdentifier(string $identifier): ?ShortUrl;
    public function codeExists(string $code): bool;
    public function customAliasExists(string $alias): bool;
    public function incrementClickCount(ShortUrl $shortUrl): void;
}