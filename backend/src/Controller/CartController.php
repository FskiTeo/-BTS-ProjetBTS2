<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\ArticleRepository;
use App\Repository\CartRepository;
use App\Repository\ClientRepository;
use App\Repository\SizesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CartController extends AbstractController
{
    #[Route('/api/cart', name: 'app_get_cart', methods: ['GET'])]
    public function getCart(ClientRepository $clientRepository, ArticleRepository $articleRepository, Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findOneBy(["email" => $clientEmail]);
            $carts = $client->getCarts();
            if(count($carts) == 0){
                return new Response( Response::HTTP_NO_CONTENT);
            }
            $cartArray = [];
            $totalPrice = 0;
            foreach ($carts as $cart) {
                $totalPrice += $cart->getArticle()->getSellingPrice() * $cart->getQuantity();
                $cartArray[] = [
                    'cart_id' => $cart->getId(),
                    'article_id' => $cart->getArticle()->getId(),
                    'article_title' => $cart->getArticle()->getArticleTitle(),
                    'selling_price' => $cart->getArticle()->getSellingPrice(),
                    'size_id' => $cart->getSize()->getId(),
                    'size_label' => $cart->getSize()->getSizeLabel(),
                    'quantity' => $cart->getQuantity()
                ];
            }
            return new JsonResponse([
                'total_price' => $totalPrice,
                'items' => $cartArray
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    #[Route('/api/cart', name: 'app_add_to_cart', methods: ['POST'])]
    public function addToCart(ClientRepository $clientRepository, ArticleRepository $articleRepository, SizesRepository $sizesRepository, Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $manager): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findOneBy(["email" => $clientEmail]);
            $parameters = json_decode($request->getContent(), true);
            $article = $articleRepository->find($parameters['article_id']);
            if(!$article){
                return new JsonResponse([
                    'error' => 'Article not found'
                ], Response::HTTP_NOT_FOUND);
            }
            $size = $sizesRepository->find($parameters['size_id']);
            if(!$size){
                return new JsonResponse([
                    'error' => 'Size not found'
                ], Response::HTTP_NOT_FOUND);
            }
            if(!is_int($parameters['quantity']) && $parameters['quantity'] <= 0){
                return new JsonResponse([
                    'error' => 'Quantity must be a positive integer'
                ], Response::HTTP_BAD_REQUEST);
            }
            $cart = new Cart();
            $cart->setClient($client);
            $cart->setArticle($article);
            $cart->setSize($size);
            $cart->setQuantity($parameters['quantity']);

            $manager->persist($cart);
            $manager->flush();
            return new JsonResponse([
                'message' => 'Article added to cart'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/cart/{cartId}', name: 'app_update_cart', methods: ['PATCH'])]
    public function updateQuantityCart(int $cartId,
                                       Request $request,
                                       CartRepository $cartRepository,
                                       ClientRepository $clientRepository,
                                       TokenStorageInterface $tokenStorageInterface,
                                       JWTTokenManagerInterface $jwtManager,
                                       EntityManagerInterface $manager):Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findOneBy(["email" => $clientEmail]);
            $cart = $cartRepository->find($cartId);
            if(!$cart){
                return new JsonResponse([
                    'error' => 'Cart not found'
                ], Response::HTTP_NOT_FOUND);
            }
            $parameters = json_decode($request->getContent(), true);
            if(!is_int($parameters['quantity']) && $parameters['quantity'] <= 0){
                return new JsonResponse([
                    'error' => 'Quantity must be a positive integer'
                ], Response::HTTP_BAD_REQUEST);
            }
            if($cart->getClient() != $client){
                return new JsonResponse([
                    'error' => 'Cart does not belong to client'
                ], Response::HTTP_FORBIDDEN);
            }
            $cart->setQuantity($parameters['quantity']);
            $manager->persist($cart);
            $manager->flush();
            return new JsonResponse([
                'message' => 'Cart updated'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/cart/{cartId}', name: 'app_delete_cart', methods: ['DELETE'])]
    public function deleteCart(int $cartId,
                               CartRepository $cartRepository,
                               ClientRepository $clientRepository,
                               TokenStorageInterface $tokenStorageInterface,
                               JWTTokenManagerInterface $jwtManager,
                               EntityManagerInterface $manager):Response
    {
        try{
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findOneBy(["email" => $clientEmail]);
            $cart = $cartRepository->find($cartId);
            if(!$cart){
                return new JsonResponse([
                    'error' => 'Cart not found'
                ], Response::HTTP_NOT_FOUND);
            }
            if($cart->getClient() != $client){
                return new JsonResponse([
                    'error' => 'Cart does not belong to client'
                ], Response::HTTP_FORBIDDEN);
            }
            $manager->remove($cart);
            $manager->flush();
            return new JsonResponse([
                'message' => 'Cart deleted'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/deletefromcart/{article_id}-{size_id}', name: 'app_removefromcart')]
    public function removeFromCart(string $article_id, string $size_id, Request $request):Response
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "DELETE"){
            $session = $request->getSession();
            $cart = $session->get("cart");
            if(!is_null($cart)){
                $deletions = 0;
                $i = 0;
                foreach($cart as $cartarticle){
                    if($cartarticle["id"] == $article_id && $cartarticle["size_id"] == $size_id){
                        unset($cart[$i]);
                        $deletions++;
                    }
                    $i++;
                }
                if($deletions>0){
                    $session->set("cart", $cart);
                    return new JsonResponse([
                        'status'=>200,
                        'value'=>'Item deleted successfully'
                    ]);
                } else {
                    return new JsonResponse([
                        'status'=>500,
                        'error'=>'No item to delete'
                    ]);
                }
            } else {
                return new JsonResponse([
                    'status' => 500,
                    'error' => 'Cart is empty'
                ]);
            }
        } else {
            return new JsonResponse([
                'status'=>500,
                'error'=>'Wrong method'
            ]);
        }
    }

}
