<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQrCodeRequest;
use App\Services\Contracts\QrCodeServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QrCodeController extends Controller
{
    public function __construct(
        private QrCodeServiceInterface $qrCodeService
    ) {}

    public function generate(StoreQrCodeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $qrCode = $this->qrCodeService->generateQrCode(
                content: $validated['content'],
                type: $validated['type'] ?? 'text',
                format: $validated['format'] ?? 'png',
                size: $validated['size'] ?? 300
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $qrCode->id,
                    'identifier' => $qrCode->identifier,
                    'content' => $qrCode->content,
                    'type' => $qrCode->type,
                    'format' => $qrCode->format,
                    'size' => $qrCode->size,
                    'image_url' => $qrCode->image_url,
                    'created_at' => $qrCode->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code'
            ], 500);
        }
    }

    public function getImage(string $identifier): Response
    {
        $qrCode = $this->qrCodeService->getQrCode($identifier);
        
        if (!$qrCode) {
            abort(404, 'QR code not found');
        }

        $imageContent = $this->qrCodeService->getQrCodeImage($identifier);
        
        if (!$imageContent) {
            abort(404, 'QR code image not found');
        }

        $mimeType = $qrCode->format === 'svg' ? 'image/svg+xml' : 'image/png';

        return response($imageContent, 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000', // 1 year
        ]);
    }
}