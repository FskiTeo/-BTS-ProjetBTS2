<?php

namespace App\Controller;

use App\Repository\SizesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SizeController extends AbstractController
{
    #[Route('/api/sizes', name: 'app_get_sizes', methods: ["GET"])]
    public function getSizes(SizesRepository $sizesRepository): Response
    {
        $sizes = $sizesRepository->findAll();

        if(!$sizes){
            return new Response(content: null, status: Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($sizes as $size) {
            $data[] = [
                'id' => $size->getId(),
                'label' => $size->getSizeLabel()
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/api/sizes/{sizeId}', name: 'app_get_single_size', methods: ["GET"])]
    public function getSingleSize(string $sizeId, SizesRepository $sizesRepository): Response
    {
        $size = $sizesRepository->findOneBy(["id"=>$sizeId]);

        if(!$size){
            return new Response(content: null, status: Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $size->getId(),
            'label' => $size->getSizeLabel()
        ];

        return new JsonResponse($data);
    }
}
