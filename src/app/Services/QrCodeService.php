<?php

namespace App\Services;

use App\Models\QrCode;
use App\Repositories\Contracts\QrCodeRepositoryInterface;
use App\Services\Contracts\QrCodeServiceInterface;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Illuminate\Support\Facades\Storage;

class QrCodeService implements QrCodeServiceInterface
{
    public function __construct(
        private QrCodeRepositoryInterface $repository
    ) {}

    public function generateQrCode(
        string $content,
        string $type = 'text',
        string $format = 'png',
        int $size = 300
    ): QrCode {
        $identifier = $this->generateUniqueIdentifier();
        $fileName = "qr_codes/{$identifier}.{$format}";

        // Generate QR code image
        $qrCode = QrCodeGenerator::format($format)
            ->size($size)
            ->generate($content);

        // Store the file
        Storage::put($fileName, $qrCode);

        return $this->repository->create([
            'identifier' => $identifier,
            'content' => $content,
            'type' => $type,
            'format' => $format,
            'size' => $size,
            'file_path' => $fileName,
        ]);
    }

    public function getQrCode(string $identifier): ?QrCode
    {
        return $this->repository->findByIdentifier($identifier);
    }

    public function getQrCodeImage(string $identifier): ?string
    {
        $qrCode = $this->repository->findByIdentifier($identifier);

        if (!$qrCode || !Storage::exists($qrCode->file_path)) {
            return null;
        }

        return Storage::get($qrCode->file_path);
    }

    private function generateUniqueIdentifier(): string
    {
        do {
            $identifier = Str::random(12);
        } while ($this->repository->identifierExists($identifier));

        return $identifier;
    }
}