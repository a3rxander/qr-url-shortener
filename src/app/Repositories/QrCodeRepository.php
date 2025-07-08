<?php

namespace App\Repositories;

use App\Models\QrCode;
use App\Repositories\Contracts\QrCodeRepositoryInterface;

class QrCodeRepository implements QrCodeRepositoryInterface
{
    public function create(array $data): QrCode
    {
        return QrCode::create($data);
    }

    public function findByIdentifier(string $identifier): ?QrCode
    {
        return QrCode::where('identifier', $identifier)->first();
    }
}