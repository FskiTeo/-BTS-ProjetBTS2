<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;



class CategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'app_get_categories', methods: ["GET"])]
    public function getCategories(CategoryRepository $categoryRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $categories = $categoryRepository->findAll();
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getCategoryName()
            ];
        }
        return new JsonResponse($data);
    }


    #[Route('/api/categories', name: 'app_post_categories', methods: ["POST"])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas accès à cette ressource")]
    public function postCategories(CategoryRepository $categoryRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $jsonrequest = json_decode($request->getContent());
        if (isset($jsonrequest->name)) {
            $categoryToAdd = new Category();

            $categoryToAdd->setCategoryName($jsonrequest->name);

            $manager->persist($categoryToAdd);
            $manager->flush();

            return new JsonResponse(["id" => $categoryToAdd->getId(), "name" => $categoryToAdd->getCategoryName()], Response::HTTP_CREATED);

        } else {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/categories/{idCategory}', name: 'app_get_single_category', methods: ["GET"])]
    public function getSingleCategory(string $idCategory, CategoryRepository $categoryRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $category = $categoryRepository->findOneBy(["id" => $idCategory]);

        if (!$category) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $returnCategory = [
            'id' => $category->getId(),
            'name' => $category->getCategoryName()
        ];
        return new JsonResponse($returnCategory);
    }

    #[Route('/api/categories/{idCategory}', name: 'app_patch_single_category', methods: ["PATCH"])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas accès à cette ressource")]
    public function patchSingleCategory(string $idCategory, CategoryRepository $categoryRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $jsoncontent = json_decode($request->getContent());
        if ($jsoncontent->name) {
            $category = $categoryRepository->findOneBy(["id" => $idCategory]);

            if (!$category) {
                return new Response(status: Response::HTTP_BAD_REQUEST);
            }

            $category->setCategoryName($jsoncontent->name);

            $manager->persist($category);
            $manager->flush();

            return new JsonResponse(["id" => $category->getId(), "name" => $category->getCategoryName()]);
        } else {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/categories/{idCategory}', name: 'app_delete_single_category', methods: ["DELETE"])]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas accès à cette ressource")]
    public function deleteSingleCategory(string $idCategory, CategoryRepository $categoryRepository, EntityManagerInterface $manager, Request $request): Response
    {
        try {
            $category = $categoryRepository->findOneBy(["id" => $idCategory]);

            if (!$category) {
                return new Response(status: Response::HTTP_BAD_REQUEST);
            }

            $manager->remove($category);
            $manager->flush();

            return new Response(Response::HTTP_NO_CONTENT);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException) {
            return new JsonResponse(["message" => "La catégorie ne peut être supprimée car des articles y sont associés."], status: Response::HTTP_BAD_REQUEST);
        } catch (\Exception) {
            return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
