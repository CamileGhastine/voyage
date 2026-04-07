<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DestinationController extends AbstractController
{
    #[Route('/destination', name: 'app_destination_index')]
    public function index(DestinationRepository $destinationRepo): Response
    {
        return $this->render('destination/index.html.twig', [
            'destinations' => $destinationRepo->findAll(),
            'mylist' => false
        ]);
    }

    #[Route('/destination/{id}', name: 'app_destination_show')]
    public function show($id, DestinationRepository $destinationRepo): Response
    {
    
        return $this->render('destination/show.html.twig', [
            'destination' => $destinationRepo->find($id)
        ]);
    }

}
