<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomShortUrlRequest;
use App\Http\Requests\StoreShortUrlRequest;
use App\Services\Contracts\ShortUrlServiceInterface;
use Illuminate\Http\JsonResponse;

class ShortUrlController extends Controller
{
    protected $shortUrlService;

    public function __construct(ShortUrlServiceInterface $shortUrlService)
    {
        $this->shortUrlService = $shortUrlService;
    }

    public function store(StoreShortUrlRequest $request): JsonResponse
    {
        try { 
 
            $shortUrl = $this->shortUrlService->createShortUrl(
                $request->validated()['original_url']
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $shortUrl->id,
                    'code' => $shortUrl->code,
                    'original_url' => $shortUrl->original_url,
                    'short_url' => $shortUrl->short_url,
                    'created_at' => $shortUrl->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create short URL'
            ], 500);
        }
    }

    public function storeCustom(StoreCustomShortUrlRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $shortUrl = $this->shortUrlService->createShortUrl(
                $validated['original_url'],
                $validated['custom_alias']
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $shortUrl->id,
                    'code' => $shortUrl->code,
                    'custom_alias' => $shortUrl->custom_alias,
                    'original_url' => $shortUrl->original_url,
                    'short_url' => $shortUrl->short_url,
                    'created_at' => $shortUrl->created_at,
                ]
            ], 201);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create custom short URL'
            ], 500);
        }
    }
}