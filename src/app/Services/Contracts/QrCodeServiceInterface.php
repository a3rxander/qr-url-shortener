<?php

namespace App\Services\Contracts;

use App\Models\QrCode;

interface QrCodeServiceInterface
{
    public function generateQrCode(
        string $content,
        string $type = 'text',
        string $format = 'png',
        int $size = 300
    ): QrCode;
    
    public function getQrCode(string $identifier): ?QrCode;
    public function getQrCodeImage(string $identifier): ?string;
}