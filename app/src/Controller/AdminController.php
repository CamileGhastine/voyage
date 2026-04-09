<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Destination;
use App\Entity\User;
use App\Form\CategoryType;
use App\Form\DestinationType;
use App\Repository\CategoryRepository;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/destination', name: 'app_admin_destination_index')]
    public function indexDestination(DestinationRepository $destinationRepo): Response
    {
        return $this->render('admin/destination/index.html.twig', [
            'destinations' => $destinationRepo->findAll()
        ]);
    }


    #[Route('/admin/destination/add', name: 'app_admin_destination_add')]
    public function addDestination(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(DestinationType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $destination = $form->getData();
            $manager->persist($destination);
            $manager->flush();
            $this->addFlash('success', 'La destination a été ajoutée avec succès.');
            
            return $this->redirectToRoute('app_admin_destination_index');
        }

        return $this->render('admin/destination/add.html.twig', [
        'formView' => $form->createView()
        ]);
    }

    #[Route('/admin/category', name: 'app_admin_category_index')]
    public function indexCategory(CategoryRepository $categoryRepo): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepo->findAll()
        ]);
    }

    #[Route('/admin/category/save/{id?null}', name: 'app_admin_category_save')]
    public function saveCategory(?Category $category, Request $request, EntityManagerInterface $manager): Response
    {
        $isUpdate = (bool)$category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'La categorie '. $category->getName() .' a été sauvegardée avec succès.');

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/category/save.html.twig', [
            'formView' => $form->createView(),
            'isUpdate' => $isUpdate
        ]);
    }
}
