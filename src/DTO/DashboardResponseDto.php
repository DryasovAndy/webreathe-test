<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Module;
use CMEN\GoogleChartsBundle\GoogleCharts\Chart;

class DashboardResponseDto
{
    protected Module $module;
    protected Chart $chart;
    protected DataBlockDto $dataBlockDto;

    public function __construct(Module $module, Chart $chart, DataBlockDto $dataBlockDto)
    {
        $this->module = $module;
        $this->chart = $chart;
        $this->dataBlockDto = $dataBlockDto;
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function getChart(): Chart
    {
        return $this->chart;
    }

    public function getDaTaBlockDto(): DataBlockDto
    {
        return $this->dataBlockDto;
    }
}