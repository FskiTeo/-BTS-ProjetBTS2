<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Order;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use App\Repository\ColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'app_register')]
    public function registerClient(ClientRepository $clientRepository, Request $request ,EntityManagerInterface $manager, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $jsoncontent = json_decode($request->getContent());
            if(isset($jsoncontent->first_name) && isset($jsoncontent->last_name) && isset($jsoncontent->email) && isset($jsoncontent->password)){
                $clientToAdd = new Client();
                $clientToAdd->setFirstName($jsoncontent->first_name);
                $clientToAdd->setLastName($jsoncontent->last_name);
                $clientToAdd->setEmail($jsoncontent->email);
                $clientToAdd->setPassword($jsoncontent->password);

                $manager->persist($clientToAdd);
                $manager->flush();

                return new JsonResponse(["id" => $clientToAdd->getId(), "client" => $clientToAdd->getEmail()], Response::HTTP_CREATED);
            } else {
                return new JsonResponse($_POST, status: Response::HTTP_OK);
            }
        } else {
            return new Response(status: Response::HTTP_METHOD_NOT_ALLOWED);
        }

    }

}