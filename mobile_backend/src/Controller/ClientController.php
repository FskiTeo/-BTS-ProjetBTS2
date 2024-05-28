<?php

namespace App\Controller;

use App\Entity\Address;
use App\Repository\AddressRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ClientController extends AbstractController
{
    #[Route('/api/client', name: 'app_get_client', methods: ['GET'])]
    public function getClient(Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, ClientRepository $clientRepository): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];
            if(!$client){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            $clientAdresses = $client->getAdresses();
            $addresses = [];

            foreach ($clientAdresses as $address) {
                $addresses[] = [
                    'id' => $address->getId(),
                    'street' => $address->getAddressStreet(),
                    'street2' => $address->getAddressStreet2(),
                    'postalcode' => $address->getAddressPostalCode(),
                    'city' => $address->getAddressCity(),
                    'country' => $address->getAddressCountry(),
                    'phonenr' => $address->getAddressPhoneNumber()
                ];
            }

            $clientArray = [
                'id' => $client->getId(),
                'first_name' => $client->getFirstName(),
                'last_name' => $client->getLastName(),
                'email' => $client->getEmail(),
                'addresses' => $addresses
            ];
            return new JsonResponse($clientArray);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/client', name: 'app_edit_client', methods: ['PATCH'])]
    function editClient(Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, ClientRepository $clientRepository, EntityManagerInterface $manager): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];
            if(!$client){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            $parameters = json_decode($request->getContent(), true);

            if(!empty($parameters['first_name'])){
                $client->setFirstName($parameters['first_name']);
            }

            if(!empty($parameters['last_name'])){
                $client->setLastName($parameters['last_name']);
            }

            if(!empty($parameters['email'])){
                $client->setEmail($parameters['email']);
            }

            if(!empty($parameters['password'])){
                $client->setPassword($parameters['password']);
            }

            $manager->persist($client);
            $manager->flush();

            return new Response('Client updated', Response::HTTP_OK);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/client/adresses', name:"app_get_client_adresses", methods: ['GET'])]
    public function getClientAdresses(Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, ClientRepository $clientRepository): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];

            if(!$client){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            $clientAdresses = $client->getAdresses();

            if(count($clientAdresses) == 0){
                return new JsonResponse([], Response::HTTP_NO_CONTENT);
            }

            $addresses = [];

            foreach ($clientAdresses as $address) {
                $addresses[] = [
                    'id' => $address->getId(),
                    'street' => $address->getAddressStreet(),
                    'street2' => $address->getAddressStreet2(),
                    'postalcode' => $address->getAddressPostalCode(),
                    'city' => $address->getAddressCity(),
                    'country' => $address->getAddressCountry(),
                    'phonenr' => $address->getAddressPhoneNumber()
                ];
            }

            return new JsonResponse($addresses);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/client/adresses', name:"app_add_client_adresses", methods: ['POST'])]
    public function addClientAdresses(Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, ClientRepository $clientRepository, EntityManagerInterface $manager): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];

            if(!$client){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            $parameters = json_decode($request->getContent(), true);

            $address = new Address();
            $address->setClient($client);
            $address->setAddressStreet($parameters["street"]);
            $address->setAddressStreet2($parameters["street2"]);
            $address->setAddressPostalCode($parameters["postalcode"]);
            $address->setAddressCity($parameters["city"]);
            $address->setAddressCountry($parameters["country"]);
            $address->setAddressPhoneNumber($parameters["phonenr"]);

            $manager->persist($address);
            $manager->flush();

            return new Response('Address added', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/client/adresses/{addressId}', name:"app_get_client_adresses_id", methods: ['GET'])]
    public function getClientAdressesId(int $addressId, Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, ClientRepository $clientRepository, AddressRepository $addressRepository): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];

            if(!$client){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            $address = $addressRepository->find($addressId);

            if(!$address){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            if($address->getClient() != $client){
                return new Response( status: Response::HTTP_FORBIDDEN);
            }

            $addressArray = [
                'id' => $address->getId(),
                'street' => $address->getAddressStreet(),
                'street2' => $address->getAddressStreet2(),
                'postalcode' => $address->getAddressPostalCode(),
                'city' => $address->getAddressCity(),
                'country' => $address->getAddressCountry(),
                'phonenr' => $address->getAddressPhoneNumber()
            ];

            return new JsonResponse($addressArray);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/client/adresses/{addressId}', name:"app_edit_client_adresses", methods: ['PATCH'])]
    public function editClientAdresses(int $addressId, Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, ClientRepository $clientRepository, AddressRepository $addressRepository, EntityManagerInterface $manager): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];

            if(!$client){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            $address = $addressRepository->find($addressId);

            if(!$address){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            if($address->getClient() != $client){
                return new Response( status: Response::HTTP_FORBIDDEN);
            }

            $parameters = json_decode($request->getContent(), true);

            if(!empty($parameters['street'])){
                $address->setAddressStreet($parameters['street']);
            }

            if(!empty($parameters['street2'])){
                $address->setAddressStreet2($parameters['street2']);
            }

            if(!empty($parameters['postalcode'])){
                $address->setAddressPostalCode($parameters['postalcode']);
            }

            if(!empty($parameters['city'])){
                $address->setAddressCity($parameters['city']);
            }

            if(!empty($parameters['country'])){
                $address->setAddressCountry($parameters['country']);
            }

            if(!empty($parameters['phonenr'])){
                $address->setAddressPhoneNumber($parameters['phonenr']);
            }

            $manager->persist($address);
            $manager->flush();

            return new Response('Address updated', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/client/adresses/{addressId}', name:"app_delete_client_adresses", methods: ['DELETE'])]
    public function deleteClientAdresses(int $addressId, Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, ClientRepository $clientRepository, AddressRepository $addressRepository, EntityManagerInterface $manager): Response
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];

            if(!$client){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            $address = $addressRepository->find($addressId);

            if(!$address){
                return new Response( status: Response::HTTP_NOT_FOUND);
            }

            if($address->getClient() != $client){
                return new Response( status: Response::HTTP_FORBIDDEN);
            }

            $manager->remove($address);
            $manager->flush();

            return new Response('Address deleted', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
