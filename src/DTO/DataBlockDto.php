<?php

declare(strict_types=1);

namespace App\DTO;

class DataBlockDto
{
    protected ?int $lastValueTic;
    protected ?int $lastNotErrorValueTic;
    protected ?int $measurementCountTic;
    protected ?float $averageValueTic;
    protected ?int $maximumValueTic;
    protected ?int $minimumValueTic;
    protected ?float $averageValueAll;
    protected ?int $maximumValueAll;
    protected ?int $minimumValueAll;
    protected ?int $measurementCountAll;
    protected ?string $timeOfWorkAll;
    protected ?bool $isWorking;

    public function __construct(array $data)
    {
        $lastTicData = end($data);
        $sumValuesAll = array_sum(array_column($data, 'sumValue'));
        $measurementsCountAll = array_sum(array_column($data, 'measurementCount'));
        $averageValueAll = $measurementsCountAll > 0 ? round($sumValuesAll / $measurementsCountAll, 1) : 0;
        $maximumValueAll = count($data) > 0 ? max(array_column($data, 'maximumValue')) : 0;
        $minimumValueAll = count($data) > 0 ? min(array_column($data, 'minimumValue')) : 0;
        $measurementCountAll = count($data) > 0 ? array_sum(array_column($data, 'measurementCount')) : 0;
        $timeOfWorkAll = $this->generateTimeOfWorkAllString($data);

        $this->lastValueTic = $lastTicData && $lastTicData['lastValue'] ? $lastTicData['lastValue'] : null;
        $this->lastNotErrorValueTic = $lastTicData && $lastTicData['lastNotErrorValue'] ? $lastTicData['lastNotErrorValue'] : null;
        $this->measurementCountTic = $lastTicData && $lastTicData['measurementCount'] ? $lastTicData['measurementCount'] : null;
        $this->averageValueTic = $lastTicData && $lastTicData['averageValue'] ? (float)$lastTicData['averageValue'] : null;
        $this->maximumValueTic = $lastTicData && $lastTicData['maximumValue'] ? (int)$lastTicData['maximumValue'] : null;
        $this->minimumValueTic = $lastTicData && $lastTicData['minimumValue'] ? (int)$lastTicData['minimumValue'] : null;
        $this->averageValueAll = $averageValueAll;
        $this->maximumValueAll = $maximumValueAll;
        $this->minimumValueAll = $minimumValueAll;
        $this->measurementCountAll = $measurementCountAll;
        $this->timeOfWorkAll = $timeOfWorkAll;
        $this->isWorking = $lastTicData && $lastTicData['successes'] && $lastTicData['successes'] !== '0';
    }

    public function getLastValueTic(): ?int
    {
        return $this->lastValueTic;
    }

    public function getLastNotErrorValueTic(): ?int
    {
        return $this->lastNotErrorValueTic;
    }

    public function getMeasurementCountTic(): ?int
    {
        return $this->measurementCountTic;
    }

    public function getAverageValueTic(): ?float
    {
        return $this->averageValueTic;
    }

    public function getMaximumValueTic(): ?int
    {
        return $this->maximumValueTic;
    }

    public function getMinimumValueTic(): ?int
    {
        return $this->minimumValueTic;
    }

    public function getAverageValueAll(): ?float
    {
        return $this->averageValueAll;
    }

    public function getMaximumValueAll(): ?int
    {
        return $this->maximumValueAll;
    }

    public function getMinimumValueAll(): ?int
    {
        return $this->minimumValueAll;
    }

    public function getMeasurementCountAll(): ?int
    {
        return $this->measurementCountAll;
    }

    public function getTimeOfWorkAll(): ?string
    {
        return $this->timeOfWorkAll;
    }

    public function isWorking(): ?bool
    {
        return $this->isWorking;
    }

    private function generateTimeOfWorkAllString(array $data): string
    {
        if (count($data) === 0) {
            return 'No data';
        }

        $seconds = count(array_filter($data, static function ($item) {
                return $item['successes'] !== '0';
            })) * 15;

        $result['days'] = floor($seconds / 86400);
        $seconds %= 86400;

        $result['hours'] = floor($seconds / 3600);
        $seconds %= 3600;

        $result['minutes'] = floor($seconds / 60);
        $result['seconds'] = $seconds % 60;

        $firstNotNullKey = array_key_first(array_filter($result));

        if ($firstNotNullKey === 'days') {
            $string = (int)$result['days'] . ' days ' . (int)$result['hours'] . ' hours ' . (int)$result['minutes'] . ' minutes ' . $result['seconds'] . ' seconds';
        } elseif ($firstNotNullKey === 'hours') {
            $string = (int)$result['hours'] . ' hours ' . (int)$result['minutes'] . ' minutes ' . $result['seconds'] . ' seconds';
        } elseif ($firstNotNullKey === 'minutes') {
            $string = (int)$result['minutes'] . ' minutes ' . $result['seconds'] . ' seconds';
        } else {
            $string = $result['seconds'] . ' seconds';
        }

        return $string;
    }
}