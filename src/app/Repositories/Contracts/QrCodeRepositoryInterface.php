<?php

namespace App\Repositories\Contracts;

use App\Models\QrCode;

interface QrCodeRepositoryInterface
{
    public function create(array $data): QrCode;
    public function findByIdentifier(string $identifier): ?QrCode;
}