<?php

namespace App\Repositories;

use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;

class ShortUrlRepository implements ShortUrlRepositoryInterface
{
    public function __construct(
        private ShortUrl $model
    ) {}

    public function create(array $data): ShortUrl
    {
        return $this->model->create($data);
    }

    public function findByCode(string $code): ?ShortUrl
    {
        return $this->model->where('code', $code)->first();
    }

    public function findByCustomAlias(string $alias): ?ShortUrl
    {
        return $this->model->where('custom_alias', $alias)->first();
    }

    public function findByIdentifier(string $identifier): ?ShortUrl
    {
        return $this->model->where('code', $identifier)
            ->orWhere('custom_alias', $identifier)
            ->first();
    }

    public function codeExists(string $code): bool
    {
        return $this->model->where('code', $code)->exists();
    }

    public function customAliasExists(string $alias): bool
    {
        return $this->model->where('custom_alias', $alias)->exists();
    }

    public function incrementClickCount(ShortUrl $shortUrl): void
    {
        $shortUrl->incrementClickCount();
    }
}