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
    ); 
}