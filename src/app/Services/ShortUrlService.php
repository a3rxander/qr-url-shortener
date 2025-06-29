<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Services\Contracts\ShortUrlServiceInterface;
use Illuminate\Support\Str;

class ShortUrlService implements ShortUrlServiceInterface
{
    public function __construct(
        private ShortUrlRepositoryInterface $repository
    ) {}

    public function createShortUrl(string $originalUrl, ?string $customAlias = null): ShortUrl
    {
        if ($customAlias && $this->repository->customAliasExists($customAlias)) {
            throw new \InvalidArgumentException('Custom alias already exists');
        }

        $code = $this->generateUniqueCode();

        return $this->repository->create([
            'code' => $code,
            'original_url' => $originalUrl,
            'custom_alias' => $customAlias,
        ]);
    }

    public function getShortUrl(string $identifier): ?ShortUrl
    {
        return $this->repository->findByIdentifier($identifier);
    }

    public function redirectAndTrack(string $identifier): ?string
    {
        $shortUrl = $this->repository->findByIdentifier($identifier);

        if (!$shortUrl || !$shortUrl->is_active) {
            return null;
        }

        $this->repository->incrementClickCount($shortUrl);

        return $shortUrl->original_url;
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::random(6);
        } while ($this->repository->codeExists($code));

        return $code;
    }

    private function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}