<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Measurement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/measurement')]
class MeasurementController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/list', name: 'measurement_list', methods: ['GET'])]
    public function measurementList(): Response
    {
        $measurements = $this->em->getRepository(Measurement::class)->findAll();

        return $this->render('admin/measurement/list.html.twig', [
            'measurements' => $measurements,
        ]);
    }
}