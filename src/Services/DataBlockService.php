<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\DataBlockDto;

readonly class DataBlockService
{
    public function createDataBlock(array $preparedData): DataBlockDto
    {
        return new DataBlockDto($preparedData);
    }
}