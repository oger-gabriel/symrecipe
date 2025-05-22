<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
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

        return $this->render("recipe/index.html.twig", [
            "recipes" => $recipes,
        ]);
    }
}
