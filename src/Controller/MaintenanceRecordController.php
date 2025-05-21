<?php

namespace App\Controller;

use App\Entity\MaintenanceRecord;
use App\Form\MaintenanceRecordForm;
use App\Repository\MaintenanceRecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/maintenance/record')]
final class MaintenanceRecordController extends AbstractController
{
    #[Route(name: 'app_maintenance_record_index', methods: ['GET'])]
    public function index(MaintenanceRecordRepository $maintenanceRecordRepository): Response
    {
        return $this->render('maintenance_record/index.html.twig', [
            'maintenance_records' => $maintenanceRecordRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_maintenance_record_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $maintenanceRecord = new MaintenanceRecord();
        $form = $this->createForm(MaintenanceRecordForm::class, $maintenanceRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($maintenanceRecord);
            $entityManager->flush();

            return $this->redirectToRoute('app_maintenance_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maintenance_record/new.html.twig', [
            'maintenance_record' => $maintenanceRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_maintenance_record_show', methods: ['GET'])]
    public function show(MaintenanceRecord $maintenanceRecord): Response
    {
        return $this->render('maintenance_record/show.html.twig', [
            'maintenance_record' => $maintenanceRecord,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_maintenance_record_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MaintenanceRecord $maintenanceRecord, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaintenanceRecordForm::class, $maintenanceRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_maintenance_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maintenance_record/edit.html.twig', [
            'maintenance_record' => $maintenanceRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_maintenance_record_delete', methods: ['POST'])]
    public function delete(Request $request, MaintenanceRecord $maintenanceRecord, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maintenanceRecord->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($maintenanceRecord);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_maintenance_record_index', [], Response::HTTP_SEE_OTHER);
    }
}
