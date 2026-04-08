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
        if (!$this->getUser() || !$destination) {
            $this->addFlash('warning', 'Vous ne pouvez pas accéder à cette page sans être connecté.');

            return $this->redirectToRoute('app_login');
        }
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

        $this->addFlash('success', 'La destination a été ajoutée à votre liste avec succès.');

        return $this->redirectToRoute('app_mylist_index');
    }

    #[Route('/mylist/remove/{id}', name: 'app_mylist_remove')]
    public function remove(Wish $wish, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser() || $this->getUser()->getWishes()->contains($wish) || !$wish) {
            $this->addFlash('warning', 'Vous ne pouvez pas accéder à cette page sans être connecté.');

            return $this->redirectToRoute('app_login');
        }

        $manager->remove($wish);
        $manager->flush();
        $this->addFlash('success', 'La destinatin a été supprimée de votre liste avec succès.');

        return $this->redirectToRoute('app_mylist_index');
    }

    #[Route('/mylist/status/{id}', name: 'app_mylist_status')]
    public function status(Wish $wish, EntityManagerInterface $manager): Response
    {

        if (!$this->getUser() || $this->getUser()->getWishes()->contains($wish) || !$wish) {
            $this->addFlash('warning', 'Vous ne pouvez pas accéder à cette page sans être connecté.');

            return $this->redirectToRoute('app_login');
        }

        if($wish->getStatus() === Wish::REVEE) {
            $wish->setStatus(Wish::VISITEE);
        } else {
            $wish->setStatus(Wish::REVEE);
        }
        $manager->flush();
        $this->addFlash('success', 'La destinatin a été changée de status avec succès.');

        return $this->redirectToRoute('app_mylist_index');
    }
}
