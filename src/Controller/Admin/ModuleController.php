<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Module;
use App\Form\ModuleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class ModuleController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/module/list', name: 'module_list', methods: ['GET'])]
    public function moduleList(): Response
    {
        $module = $this->em->getRepository(Module::class)->findAll();

        return $this->render('admin/module/list.html.twig', [
            'modules' => $module,
        ]);
    }

    #[Route('/module/create', name: 'module_create', methods: ['GET', 'POST'])]
    public function moduleCreate(Request $request): Response
    {
        $module = new Module();

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Module $module */
            $module = $form->getData();

            $this->em->persist($module);
            $this->em->flush();

            $this->addFlash(
                'notice',
                'Module added: ' . $module->getName()
            );

            return $this->redirectToRoute('module_create');
        }

        return $this->render('admin/module/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/module/{id}/delete', name: 'module_delete', methods: ['GET', 'DELETE'])]
    public function moduleDelete(Module $module): Response
    {
        $moduleId = $module->getId();

        $this->em->remove($module);
        $this->em->flush();

        $this->addFlash(
            'warning',
            'Module was deleted'
        );

        return $this->render('admin/module/delete.html.twig', [
            'module' => $module,
            'id' => $moduleId,
        ]);
    }

    #[Route('/module/{id}/edit', name: 'module_edit', methods: ['GET', 'POST'])]
    public function moduleEdit(Request $request, Module $module): Response
    {
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Module $module */
            $data = $form->getData();
            $module->setName($data->getName());
            $module->setUnit($data->getUnit());

            $this->em->persist($module);
            $this->em->flush();

            $this->addFlash(
                'warning',
                'Data was changed'
            );

            return $this->redirectToRoute('module_edit', ['id' => $module->getId(), 'module' => $module]);
        }

        return $this->render('admin/module/edit.html.twig', [
            'form' => $form->createView(),
            'module' => $module,
        ]);
    }

    #[Route('/module/{id}/show', name: 'module_show', methods: ['GET'])]
    public function moduleShow(Module $module): Response
    {
        return $this->render('admin/module/show.html.twig', [
            'module' => $module,
        ]);
    }
}