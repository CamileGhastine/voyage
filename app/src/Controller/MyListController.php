<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Entity\Wish;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MyListController extends AbstractController
{
    #[Route('/mylist', name: 'app_mylist_index')]
    public function index(): Response
    {
        $wishes = $this->getUser()->getWishes() 
        ? $this->getUser()->getWishes()
        : null;

        return $this->render('mylist/index.html.twig', [
            'wishes' => $wishes
        ]);
    }

    #[Route('/mylist/add/{id}', name: 'app_mylist_add')]
    public function add(Destination $destination, EntityManagerInterface $manager): Response
    {
        if($this->getUser()->hasWish($destination)) {
            $this->addFlash('warning', 'Cette desitination est déjà dans votre liste de voyage.');

            return $this->redirectToRoute('app_destination_index');
        }
   
        $wish = new Wish();
        $wish->setStatus(Wish::REVEE)
        ->setDestination($destination);
        
        $user = $this->getUser();
        $user->addWish($wish);
   
        $manager->persist($wish);
        $manager->flush();

        return $this->redirectToRoute('app_mylist_index');
    }

    #[Route('/mylist/remove/{id}', name: 'app_mylist_remove')]
    public function remove(Wish $wish, EntityManagerInterface $manager): Response
    {
        $manager->remove($wish);
        $manager->flush();

        return $this->redirectToRoute('app_mylist_index');
    }
}
