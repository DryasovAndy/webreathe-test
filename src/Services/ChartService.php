<?php

declare(strict_types=1);

namespace App\Services;

use CMEN\GoogleChartsBundle\GoogleCharts\Chart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ComboChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\VAxis;

readonly class ChartService
{
    private const CHART_AXIS_AVERAGE_VALUES_CONST_COEFFICIENT = 1.2;
    private const CHART_AXIS_ERRORS_CONST_COEFFICIENT = 2;
    private const CHART_WIDTH = 900;
    private const CHART_HEIGHT = 500;

    public function createChart(array $preparedData): ComboChart
    {
        $chart = new ComboChart();

        $this->generateData($chart, $preparedData);
        $this->generateTitle($chart);
        $this->generateDimensions($chart);
        $this->generateHAxis($chart);
        $this->generateVAxis($chart);
        $this->generateVAxes($chart, $preparedData);
        $this->generateSeries($chart);

        return $chart;
    }

    public function generateData(Chart $chart, array $preparedData): void
    {
        $arrayData = [];

        foreach ($preparedData as $data) {
            $arrayData[] = [$data['formattedDate'], (int)$data['errors'], (int)$data['averageValue']];
        }

        if (empty($preparedData)) {
            $arrayData = [
                ['', ['role' => 'annotation']],
                ['', ''],
            ];
        } else {
            array_unshift($arrayData, ['Date', 'Errors', 'AverageValue']);
        }

        $chart->getData()->setArrayToDataTable($arrayData);
    }

    private function generateTitle(ComboChart $chart): void
    {
        $chart->getOptions()->setTitle('Last 5 minutes graph');
    }

    private function generateDimensions(ComboChart $chart): void
    {
        $chart->getOptions()->setWidth(self::CHART_WIDTH)->setHeight(self::CHART_HEIGHT);
    }

    private function generateHAxis(ComboChart $chart): void
    {
        $chart->getOptions()->getHAxis()->setTitle('date');
    }

    private function generateVAxes(ComboChart $chart, array $preparedData): void
    {
        $maxErrorsCount = 0;
        $maxAverageValue = 0;

        if (array_key_exists('errors', $preparedData)) {
            $maxErrorsCount = max(array_column($preparedData, 'errors')) * self::CHART_AXIS_ERRORS_CONST_COEFFICIENT;
        }
        if (array_key_exists('averageValue', $preparedData)) {
            $maxAverageValue = max(array_column($preparedData, 'averageValue')) * self::CHART_AXIS_AVERAGE_VALUES_CONST_COEFFICIENT;
        }

        $chart
            ->getOptions()
            ->setVAxes([
                (new VAxis())->setTitle('errors count in tic')->setMaxValue((int)$maxErrorsCount),
                (new VAxis())->setTitle('average values in tic')->setMaxValue((int)$maxAverageValue)
            ]);
    }

    private function generateVAxis(ComboChart $chart): void
    {
        $chart->getOptions()->getVAxis()->setViewWindowMode('explicit');
        $chart->getOptions()->getVAxis()->getGridlines()->setColor('transparent');
    }

    private function generateSeries(ComboChart $chart): void
    {
        $chart->getOptions()
            ->setColors(['#B00020', '#000000'])
            ->setSeries([
                ['type' => 'bars', 'targetAxisIndex' => 0],
                ['type' => 'line', 'targetAxisIndex' => 1]
            ]);
    }
}