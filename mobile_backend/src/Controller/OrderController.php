<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use App\Repository\ColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderController extends AbstractController
{
    #[Route('/api/create_order', name: 'app_order')]
    public function createOrder(ArticleRepository $articleRepository,ClientRepository $clientRepository,Request $request ,EntityManagerInterface $manager, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];
            $id = $client->getId();

            $parameters = json_decode($request->getContent(), true);
            $article = $articleRepository->find($parameters['article_id']);



            $order = new Order();
            $order->setClientId($client[0]);
            $order->setOrderDatetime(new \DateTime());
            $order->setOrderStatus('Ordered');
            $order->addArticle($article);
        } else {
            return new Response('wrong method', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}
