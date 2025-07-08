<?php

namespace App\Services;
 
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel; 
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\Contracts\QrCodeServiceInterface;
use App\Repositories\Contracts\QrCodeRepositoryInterface;

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
    ): array {

        // Generar identificador único
        $identifier = $this->generateUniqueIdentifier();

        // Crear el QR con Endroid
        $qrCode = new EndroidQrCode(
            data: $content,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: $size,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        // Escribir el archivo usando PngWriter
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Generar nombre de archivo
        $fileName = $identifier . '.' . $format;
        $filePath = 'qr-codes/' . $fileName;

        // Guardar en storage
        Storage::disk('public')->put($filePath, $result->getString());

        // Guardar en base de datos
        $qrCodeRecord = $this->repository->create([
            'identifier' => $identifier,
            'content' => $content,
            'type' => $type,
            'format' => $format,
            'size' => $size,
            'file_path' => $filePath
        ]);

        return [
            'id' => $qrCodeRecord->id,
            'identifier' => $identifier,
            'content' => $content,
            'type' => $type,
            'format' => $format,
            'size' => $size,
            'file_path' => $filePath,
            'file_url' => Storage::disk('public')->url($filePath),
            'data_uri' => $result->getDataUri(),
            'created_at' => $qrCodeRecord->created_at
        ];
    }

    private function generateUniqueIdentifier(): string
    {
        do {
            $identifier = Str::random(20);
        } while ($this->repository->findByIdentifier($identifier));

        return $identifier;
    }
}