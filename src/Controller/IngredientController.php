<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Ingredient;
use App\Form\IngredientType;

final class IngredientController extends AbstractController
{
    #[Route("/ingredient", name: "app_ingredient")]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt(key: "page", default: 1),
            limit: 10
        );

        return $this->render("pages/ingredient/index.html.twig", [
            "controller_name" => "IngredientController",
        ]);
    }

    #[Route("/ingredient/nouveau", "ingredient_new", methods: ["GET", "POST"])]
    public function new(Request $request, EntityManager $manager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            return $this->redirectToRoute("app_ingredient");
        }

        return $this->render("pages/ingredient/new.htmt.twig", [
            "form" => $form,
        ]);
    }
}
