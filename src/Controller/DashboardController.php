<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VehicleRepository;
use App\Repository\MaintenanceRecordRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        Request $request,
        VehicleRepository $vehicleRepo,
        MaintenanceRecordRepository $recordRepo
    ): Response {
        $user = $this->getUser();
        $vehicles = $vehicleRepo->findBy(['user' => $user]);
        $upcoming = $recordRepo->findUpcomingForUser($user);

        $vehicleId = $request->query->get('vehicle');
        $fromDate = $request->query->get('from');
        $toDate = $request->query->get('to');

        $records = $recordRepo->findFilteredForUser($user, $vehicleId, $fromDate, $toDate);
        $lastService = $recordRepo->findLastMaintenanceForUser($user);

        return $this->render('dashboard/index.html.twig', [
            'vehicleCount' => count($vehicles),
            'recordCount' => count($records),
            'lastService' => $lastService?->getDate(),
            'upcomingRecords' => $upcoming,
            'vehicles' => $vehicles,
            'records' => $records,
            'filters' => compact('vehicleId', 'fromDate', 'toDate'),
        ]);
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(
        VehicleRepository $vehicleRepo,
        MaintenanceRecordRepository $recordRepo
    ): Response {
        return $this->render('dashboard/admin.html.twig', [
            'allVehicles' => $vehicleRepo->findAll(),
            'allRecords' => $recordRepo->findAll(),
        ]);
    }
}
