<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ShortUrlServiceInterface;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    public function __construct(
        private ShortUrlServiceInterface $shortUrlService
    ) {}

    public function redirect(string $identifier): RedirectResponse
    {
        $originalUrl = $this->shortUrlService->redirectAndTrack($identifier);

        if (!$originalUrl) {
            abort(404, 'Short URL not found or inactive');
        }

        return redirect($originalUrl, 302);
    }
}