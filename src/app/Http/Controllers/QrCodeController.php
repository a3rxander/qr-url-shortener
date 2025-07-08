<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQrCodeRequest;
use App\Services\Contracts\QrCodeServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeServiceInterface $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    } 

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
                'data' => $qrCode,
                'message' => 'QR code generated successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $identifier): JsonResponse
    {
        try {
            $qrCode = $this->qrCodeService->findByIdentifier($identifier);
            
            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $qrCode->id,
                    'identifier' => $qrCode->identifier,
                    'content' => $qrCode->content,
                    'type' => $qrCode->type,
                    'format' => $qrCode->format,
                    'size' => $qrCode->size,
                    'file_path' => $qrCode->file_path,
                    'file_url' => Storage::disk('public')->url($qrCode->file_path),
                    'created_at' => $qrCode->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve QR code'
            ], 500);
        }
    }
} 