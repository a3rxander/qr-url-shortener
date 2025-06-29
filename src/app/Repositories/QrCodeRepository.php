<?php

namespace App\Repositories;

use App\Models\QrCode;
use App\Repositories\Contracts\QrCodeRepositoryInterface;

class QrCodeRepository implements QrCodeRepositoryInterface
{
    public function __construct(
        private QrCode $model
    ) {}

    public function create(array $data): QrCode
    {
        return $this->model->create($data);
    }

    public function findByIdentifier(string $identifier): ?QrCode
    {
        return $this->model->where('identifier', $identifier)->first();
    }

    public function identifierExists(string $identifier): bool
    {
        return $this->model->where('identifier', $identifier)->exists();
    }
}