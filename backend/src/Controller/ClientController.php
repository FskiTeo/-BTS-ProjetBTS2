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
    #[Route('/api/getinfos', name: 'app_get_client_infos')]
    public function getClientInfos(ClientRepository $clientRepository, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
        $clientEmail = $decodedJwtToken["email"];
        $client = $clientRepository->findBy(["email" => $clientEmail])[0];
        $id = $client->getId();
        $first_name = $client->getFirstName();
        $last_name = $client->getLastName();
        $email = $client->getEmail();
        $password = $client->getPassword();

        $clientInfos = [
            'id' => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            'email' => $email,
            'password' => $password
        ];

        return new JsonResponse($clientInfos);

    }

    #[Route('/api/editinfos', name: 'app_edit_client_infos')]
    public function editClientInfos(ClientRepository $clientRepository, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, Request $request): Response
    {
        if ($_SERVER["REQUEST_METHOD"] == "PUT") {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];
            $id = $client->getId();

            $parameters = json_decode($request->getContent(), true);
            $emailInput = filter_var($parameters["email_update"], FILTER_SANITIZE_EMAIL);

            if ($emailInput != false) {
                $exec = $clientRepository->updateClientFields($id, $parameters["first_name_update"], $parameters["last_name_update"], $emailInput, password_hash($parameters["password_update"], PASSWORD_BCRYPT));
                if ($exec) {
                    return new Response("ok", Response::HTTP_OK);
                } else {
                    return new Response('sql error', Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new Response('email not valid', Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new Response('wrong method', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/get_addresses', name: 'app_get_address')]
    public function getAddresses(ClientRepository $clientRepository, AddressRepository $addressRepository, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "GET") {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];
            $id = $client->getId();

            $addresses_id = $clientRepository->findAddresses($id);
            $addresses = [];
            foreach ($addresses_id as $address_id) {
                $address = $addressRepository->find($address_id['id']);
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
            if (count($addresses) > 0) {
                return new JsonResponse($addresses);
            } else {
                return new Response('User has no addresses', Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new Response('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/add_address', name: 'add_address')]
    public function addAddress(ClientRepository $clientRepository, Request $request, EntityManagerInterface $manager, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST") {
            $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
            $clientEmail = $decodedJwtToken["email"];
            $client = $clientRepository->findBy(["email" => $clientEmail])[0];
            $id = $client->getId();

            $parameters = json_decode($request->getContent(), true);

            $address = new Address();
            $address->setClient($clientRepository->find($id));
            $address->setAddressStreet($parameters["address1"]);
            if (isset($parameters["address2"])) {
                $address->setAddressStreet2($parameters["address2"]);
            }
            $address->setAddressPostalCode($parameters["postcode"]);
            $address->setAddressCity($parameters["city"]);
            $address->setAddressCountry($parameters["country"]);
            $address->setAddressPhoneNumber($parameters["phonenumber"]);

            $manager->persist($address);
            $manager->flush();

            return new Response('Address added successfully', Response::HTTP_OK);
        } else {
            return new Response('Incorrect Method', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    #[Route('/api/remove_address/{address_id}', name: 'app_remove_address', methods: ["DELETE"])]
    public function removeAddress(string $address_id, ClientRepository $clientRepository, AddressRepository $addressRepository, Request $request, EntityManagerInterface $manager, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
        $clientEmail = $decodedJwtToken["email"];
        $client = $clientRepository->findBy(["email" => $clientEmail])[0];
        $id = $client->getId();

        $address = $addressRepository->find($address_id);
        $address_client_id = $address->getClient()->getId();

        if ($address_client_id == $id) {
            $manager->remove($address);
            $manager->flush();
            return new Response('Address deleted', Response::HTTP_OK);
        } else {
            return new Response('The address is not an address of the client', Response::HTTP_BAD_REQUEST);
        }
    }
}
