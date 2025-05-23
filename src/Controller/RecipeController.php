<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

final class RecipeController extends AbstractController
{
    #[Route("/recipe", name: "app_recipe")]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recipes = $paginator->paginate($repository->findAll(), $request->query->getInt(key: "page", default: 1), limit: 25);

        return $this->render("pages/recipe/index.html.twig", [
            "recipes" => $recipes,
        ]);
    }

    #[Route("/recipe/nouveau", name: "recipe_new", methods: ["GET", "POST"])]
    public function new(Request $request, EntityManager $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash("success", "Votre recette a été créée avec succès !");

            return $this->redirectToRoute("app_recipe");
        }

        return $this->render("pages/recipe/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/recipe/edit/{id}", name: "recipe_edit", methods: ["GET", "POST"])]
    public function edit(Request $request, EntityManager $manager, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->flush();

            $this->addFlash("success", "Vos changements ont été enregistrés !");

            return $this->redirectToRoute("app_recipe");
        }

        return $this->render("pages/recipe/edit.html.twig", [
            "form" => $form->createView(),
        ]);
    }
    #[Route("/recipe/remove/{id}", "recipe_remove", methods: ["GET"])]
    public function remove(Request $request, EntityManager $manager, Recipe $recipe): Response
    {
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash("success", "L'a recette a été supprimé !");

        return $this->redirectToRoute("app_recipe");
    }
}
