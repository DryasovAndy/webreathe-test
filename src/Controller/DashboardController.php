<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\DashboardResponseDto;
use App\Repository\ModuleRepository;
use App\Services\ChartService;
use App\Services\DataBlockService;
use App\Services\MeasurementService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    public function __construct(
        private readonly ModuleRepository $moduleRepository,
        private readonly MeasurementService $measurementService,
        private readonly ChartService       $chartService,
        private readonly DataBlockService   $dataBlockService
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(name: 'dashboard', methods: ['GET'])]
    public function showDashboard(): Response
    {
        $modules = $this->moduleRepository->findAll();
        $dashboardsResponseDto = [];

        foreach ($modules as $module) {
            $preparedData = $this->measurementService->prepareData($module->getId());
            $chart = $this->chartService->createChart($preparedData);
            $dataBlock = $this->dataBlockService->createDataBlock($preparedData);
            $dashboardsResponseDto[] = new DashboardResponseDto($module, $chart, $dataBlock);
        }

        return $this->render('dashboard.html.twig', ['dashboardsResponseDto' => $dashboardsResponseDto]);
    }
}