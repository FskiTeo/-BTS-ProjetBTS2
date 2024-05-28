<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Stock;
use App\Repository\ArticlePictureRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\ColorRepository;
use App\Repository\SizesRepository;
use App\Repository\StockRepository;
use ContainerXxf60Ix\getDebug_ArgumentResolver_RequestAttributeService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes\Property;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArticleController extends AbstractController
{
    private function getArticleInfos($article, $articleRepository): array
    {
        $id = $article->getId();

        return [
            'id' => $id,
            'title' => $article->getArticleTitle(),
            'description' => $article->getArticleDescription(),
            'selling_price' => $article->getSellingPrice(),
            'pictures' => $articleRepository->getArticlePictures($id),
            'color' => $articleRepository->getArticleColors($id),
            'sizes' => $articleRepository->getAvailableArticleSizes($id),
            'category' => $articleRepository->getArticleCategory($id)
        ];
    }

    private function getFullArticleInfos($article, $articleRepository): array
    {
        return [
            'id' => $article->getId(),
            'title' => $article->getArticleTitle(),
            'description' => $article->getArticleDescription(),
            'selling_price' => $article->getSellingPrice(),
            'pictures' => $article->getArticlePictures(),
            'colors' => $articleRepository->getArticleColors($article->getId()),
            'sizes_stock' => $articleRepository->getArticleSizesWithStock($article->getId()),
            'category' => $articleRepository->getArticleCategory($article->getId())
        ];
    }

    #[Route('/api/articles', name: 'app_get_articles', methods: ["GET"])]
    public function getArticles(ArticleRepository $articleRepository, ): Response
    {
        try {

            $articles = $articleRepository->findAll();

            if (!$articles) {
                return new Response(Response::HTTP_NOT_FOUND);
            }

            $data = [];

            foreach ($articles as $article) {

                $data[] = $this->getArticleInfos($article, $articleRepository);

            }

            return new JsonResponse($data);
        } catch (Exception $exception) {
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/articles', name: 'app_post_article', methods: ["POST"])]
    #[IsGranted("ROLE_ADMIN", message: "You are not allowed to access this ressource")]
    public function postArticles(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $manager): Response
    {
        try {
            $jsonContent = json_decode($request->getContent());

            $category = $categoryRepository->findOneBy(["id" => $jsonContent->categoryId]);

            $newArticle = new Article();

            $newArticle->setArticleTitle($jsonContent->title);
            $newArticle->setArticleDescription($jsonContent->description);
            $newArticle->setSellingPrice($jsonContent->price);
            $newArticle->setCategory($category);

            $manager->persist($newArticle);
            $manager->flush();

            return new JsonResponse($newArticle, Response::HTTP_CREATED);

        } catch (Exception $exception) {
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/articles/{id}', name: 'app_get_single_product', methods: ["GET"])]
    public function getSingleProduct(int $id, ArticleRepository $articleRepository): Response
    {
        try {

            $article = $articleRepository->find($id);

            if (!$article) {
                return new Response(Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse($this->getArticleInfos($article, $articleRepository), Response::HTTP_OK);

        } catch (\Exception $exception) {
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/articles/{id}', name: 'app_patch_single_product', methods: ["PATCH"])]
    #[IsGranted("ROLE_ADMIN", message: "You are not allowed to access this ressource")]
    public function patchSingleProduct(int $id, ArticleRepository $articleRepository, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $manager): Response
    {
        try{
            $article = $articleRepository->find($id);

            if(!$article){
                return new Response(Response::HTTP_NOT_FOUND);
            }

            $decodedContent = json_decode($request->getContent());

            if(isset($decodedContent->title)){
                $article->setArticleTitle($decodedContent->title);
            }

            if(isset($decodedContent->description)){
                $article->setArticleDescription($decodedContent->description);
            }

            if(isset($decodedContent->price)){
                $article->setSellingPrice($decodedContent->price);
            }

            if(isset($decodedContent->categoryId)){
                $category = $categoryRepository->find($decodedContent->categoryId);
                if(!$category){
                    return new Response("Category Not Found", Response::HTTP_NOT_FOUND);
                }
                $article->setCategory($category);
            }

            $manager->persist($article);
            $manager->flush();

            return new JsonResponse($this->getArticleInfos($article, $articleRepository), Response::HTTP_OK);

        } catch (\Exception $exception){
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/articles/{id}', name: 'app_delete_single_product', methods: ["DELETE"])]
    #[IsGranted("ROLE_ADMIN", message: "You are not allowed to access this ressource")]
    public function deleteSingleProduct(int $id, ArticleRepository $articleRepository,EntityManagerInterface $manager):Response
    {
        try {
            $article = $articleRepository->find($id);

            if(!$article){
                return new Response(status: Response::HTTP_NOT_FOUND);
            }

            $manager->remove($article);
            $manager->flush();

            return new Response(status: Response::HTTP_OK);
        } catch (\Exception $exception){
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/articles/stock/{articleId}', name: 'app_get_article_with_stocks', methods: ["GET"])]
    #[IsGranted("ROLE_ADMIN", message: "You are not allowed to access this ressource")]
    public function getArticleStock(string $articleId, ArticleRepository $articleRepository, ): Response
    {
        try {
            $article = $articleRepository->find($articleId);

            if (!$article) {
                return new Response(Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse(data: $this->getFullArticleInfos($article, $articleRepository), status: Response::HTTP_OK);

        } catch (Exception $exception) {
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/api/articles/stock/{articleId}', name: 'app_post_stocks', methods: ["POST"])]
    #[IsGranted("ROLE_ADMIN", message: "You are not allowed to access this ressource")]
    public function postArticleStock(string $articleId, Request $request, ArticleRepository $articleRepository, SizesRepository $sizesRepository): Response
    {
        try {
            $article = $articleRepository->findOneBy(["id" => $articleId]);

            if (!$article) {
                return new Response(Response::HTTP_BAD_REQUEST);
            }

            $jsonrequest = json_decode($request->getContent());

            foreach ($jsonrequest->listStocksToAdd as $itemToAdd) {
                $jsonItem = json_decode($itemToAdd);
                $sizeId = $jsonItem->sizeId;
                $amount = $jsonItem->amount;

                $size = $sizesRepository->find($sizeId);

                $stockEntry = new Stock();
                $stockEntry->setSize($size);
                $stockEntry->setAmount($amount);
                $stockEntry->setArticle($article);

            }

            $article = $articleRepository->find($articleId);

            if (!$article) {
                return new Response(Response::HTTP_NOT_FOUND);
            }

            $data = [
                'id' => $article->getId(),
                'title' => $article->getArticleTitle(),
                'description' => $article->getArticleDescription(),
                'selling_price' => $article->getSellingPrice(),
                'pictures' => $article->getArticlePictures(),
                'colors' => $articleRepository->getArticleColors($article->getId()),
                'sizes_stock' => $articleRepository->getArticleSizesWithStock($article->getId()),
                'category' => $articleRepository->getArticleCategory($article->getId())
            ];

            return new JsonResponse($data, status: Response::HTTP_OK);
        } catch (Exception $exception) {
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/api/articles/stock/{articleId}/{stockId}', name: 'app_patch_stocks', methods: ["PATCH"])]
    #[IsGranted("ROLE_ADMIN", message: "You are not allowed to access this ressource")]
    public function patchArticleStock(int $articleId, int $stockId, Request $request, EntityManagerInterface $manager, ArticleRepository $articleRepository, StockRepository $stockRepository, SizesRepository $sizesRepository):Response
    {
        try {
            $article = $articleRepository->find($articleId);

            if (!$article) {
                return new Response(content: "Article not found", status: Response::HTTP_NOT_FOUND);
            }

            $stock = $stockRepository->find($stockId);

            if(!$stock){
                return new Response(content: "Stock not found", status: Response::HTTP_NOT_FOUND);
            }

            if($stock->getArticle()->getId() == $article->getId()){

                $jsonContent = json_decode($request->getContent());

                if(isset($jsonContent->amount)){
                    $stock->setAmount($jsonContent->amount);
                }

                if(isset($jsonContent->sizeId)){
                    $size = $sizesRepository->find($jsonContent->sizeId);
                    if(!$size){
                        return new Response(content: "Size not found", status: Response::HTTP_NOT_FOUND);
                    }
                    $stock->setSize($size);
                }

                $manager->persist($stock);
                $manager->flush();

                return new JsonResponse(data: $this->getFullArticleInfos($article, $articleRepository), status: Response::HTTP_OK);
            } else {
                return new Response(content: "stockId is not a stock of articleId", status: Response::HTTP_BAD_REQUEST);
            }

        } catch (\Exception $exception){
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/articles/stock/{articleId}/{stockId}', name: 'app_delete_stocks', methods: ["DELETE"])]
    #[IsGranted("ROLE_ADMIN", message: "You are not allowed to access this ressource")]
    public function deleteArticleStock(int $articleId, int $stockId, EntityManagerInterface $manager, ArticleRepository $articleRepository, StockRepository $stockRepository):Response
    {
        try{
            $stock = $stockRepository->find($stockId);
            $article = $articleRepository->find($articleId);

            if($stock->getArticle()->getId() == $article->getId()){
                $manager->remove($stock);
                $manager->flush();

                return new Response(content: "Deleted.", status: Response::HTTP_OK);
            } else {
                return new Response(content: "stockId is not a stock of articleId", status: Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $exception){
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   /* #[Route('/api/articles/filter/color/{color_id}', name: 'app_get_products_by_color', methods: ["GET"])]
    public function getProductsByColor(int $color_id, ArticleRepository $articleRepository): Response
    {
        try {
           $article = $articleRepository->findArticlesByColor($color_id);

        } catch (\Exception $exception) {
            return new Response(content: $exception, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }*/







    #[Route('/api/articles/filter/color/{color_id}', name: 'app_get_products_by_color')]
    public function getProductsByColor(string $color_id, ArticleRepository $articleRepository, ArticlePictureRepository $articlePictureRepository, ColorRepository $colorRepository): Response
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "GET") {
            $articles = $articleRepository->findArticlesByColor($color_id);

            $data = [];

            foreach ($articles as $article) {
                $id = $article["id"];
                $title = $article["article_title"];
                $selling_price = $article["selling_price"];
                $pictures = $articleRepository->getArticlePictures($id);
                $colors = $articleRepository->getArticleColors($id);
                $category = $articleRepository->getArticleCategory($id);

                $data[] = [
                    'id' => $id,
                    'title' => $title,
                    'selling_price' => $selling_price,
                    'pictures' => $pictures,
                    'colors' => $colors,
                    'category' => $category
                ];
            }

            return new JsonResponse($data);
        } else {
            return new Response('Wrong method', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/articles/filter/size/{size_id}', name: 'app_get_products_by_size')]
    public function getArticleBySize(string $size_id, ArticleRepository $articleRepository): Response
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "GET") {
            $articles = $articleRepository->findArticlesBySize($size_id);

            $data = [];

            foreach ($articles as $article) {
                $id = $article["id"];
                $title = $article["article_title"];
                $selling_price = $article["selling_price"];
                $pictures = $articleRepository->getArticlePictures($id);
                $colors = $articleRepository->getArticleColors($id);
                $sizes = $articleRepository->getAvailableArticleSizes($id);
                $category = $articleRepository->getArticleCategory($id);

                $data[] = [
                    'id' => $id,
                    'title' => $title,
                    'selling_price' => $selling_price,
                    'pictures' => $pictures,
                    'colors' => $colors,
                    'available_sizes' => $sizes,
                    'category' => $category
                ];
            }

            return new JsonResponse($data);
        } else {
            return new Response('Wrong method', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/articles/filter/category/{category_id}', name: 'app_get_products_by_category')]
    public function getArticleByCategory(string $category_id, ArticleRepository $articleRepository): Response
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "GET") {
            $articles = $articleRepository->findArticlesByCategory($category_id);

            $data = [];

            foreach ($articles as $article) {
                $id = $article["id"];
                $title = $article["article_title"];
                $selling_price = $article["selling_price"];
                $pictures = $articleRepository->getArticlePictures($id);
                $colors = $articleRepository->getArticleColors($id);
                $sizes = $articleRepository->getAvailableArticleSizes($id);
                $category = $articleRepository->getArticleCategory($id);

                $data[] = [
                    'id' => $id,
                    'title' => $title,
                    'selling_price' => $selling_price,
                    'pictures' => $pictures,
                    'colors' => $colors,
                    'available_sizes' => $sizes,
                    'category' => $category
                ];
            }

            return new JsonResponse($data);
        } else {
            return new Response('Wrong method', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }


}
