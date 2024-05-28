<?php

namespace App\Controller;

use App\Repository\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ColorController extends AbstractController
{
    #[Route('/api/colors', name: 'app_get_colors')]
    public function getColors(ColorRepository $colorRepository): Response
    {
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            $colors = $colorRepository->findAll();
            $data = [];
            foreach ($colors as $color){
                $data[] = [
                    'id'=>$color->getId(),
                    'label'=>$color->getColorLabel()
                ];
            }
            return new JsonResponse($data);
        } else {
            return new Response('wrong method', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}
