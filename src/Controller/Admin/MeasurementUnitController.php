<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\MeasurementUnit;
use App\Form\MeasurementUnitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class MeasurementUnitController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/unit/list', name: 'unit_list', methods: ['GET'])]
    public function unitList(): Response
    {
        $units = $this->em->getRepository(MeasurementUnit::class)->findAll();

        return $this->render('admin/unit/list.html.twig', [
            'units' => $units,
        ]);
    }

    #[Route('/unit/create', name: 'unit_create', methods: ['GET', 'POST'])]
    public function unitCreate(Request $request): Response
    {
        $unit = new MeasurementUnit();

        $form = $this->createForm(MeasurementUnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MeasurementUnit $unit */
            $unit = $form->getData();

            $this->em->persist($unit);
            $this->em->flush();

            $this->addFlash(
                'notice',
                'Unit added: ' . $unit->getName() . ' (' . $unit->getDesignation() . ')'
            );

            return $this->redirectToRoute('unit_create');
        }

        return $this->render('admin/unit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/unit/{id}/delete', name: 'unit_delete', methods: ['GET', 'DELETE'])]
    public function unitDelete(MeasurementUnit $unit): Response
    {
        $unitId = $unit->getId();

        $this->em->remove($unit);
        $this->em->flush();

        $this->addFlash(
            'warning',
            'Unit was deleted'
        );

        return $this->render('admin/unit/delete.html.twig', [
            'unit' => $unit,
            'id' => $unitId,
        ]);
    }

    #[Route('/unit/{id}/edit', name: 'unit_edit', methods: ['GET', 'POST'])]
    public function unitEdit(Request $request, MeasurementUnit $unit): Response
    {
        $form = $this->createForm(MeasurementUnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MeasurementUnit $unit */
            $data = $form->getData();
            $unit->setName($data->getName());
            $unit->setDesignation($data->getDesignation());

            $this->em->persist($unit);
            $this->em->flush();

            $this->addFlash(
                'warning',
                'Data was changed'
            );

            return $this->redirectToRoute('unit_edit', ['id' => $unit->getId(), 'unit' => $unit]);
        }

        return $this->render('admin/unit/edit.html.twig', [
            'form' => $form->createView(),
            'unit' => $unit,
        ]);
    }

    #[Route('/unit/{id}/show', name: 'unit_show', methods: ['GET'])]
    public function unitShow(MeasurementUnit $unit): Response
    {
        return $this->render('admin/unit/show.html.twig', [
            'unit' => $unit,
        ]);
    }
}