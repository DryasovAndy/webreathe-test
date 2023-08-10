<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\MeasurementRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;

readonly class MeasurementService
{

    public function __construct(private MeasurementRepository $measurementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function prepareData(?int $moduleId): array
    {
        $currenDateTime = new DateTimeImmutable();
        $currenDateTimeTimestamp = $currenDateTime->format('Y-m-d H:i:s');
        $timeEnd = round(strtotime($currenDateTimeTimestamp) / 15) * 15;
        $timeStart = $timeEnd - 5 * 60;

        $result = [];

        if ($moduleId) {
            $result = $this->measurementRepository->getModuleData($moduleId, $timeStart, $timeEnd);
        }

        return $result;
    }
}