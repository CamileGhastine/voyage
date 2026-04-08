<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/destination', name: 'app_admin_destination')]
    public function destination(DestinationRepository $destinationRepo): Response
    {
        return $this->render('admin/destination.html.twig', [
            'destinations' => $destinationRepo->findAll()
        ]);
    }


    #[Route('/admin/destination/add', name: 'app_admin_destination_add')]
    public function addDestination(): Response
    {
        return $this->render('admin/addDestination.html.twig');
    }
}
