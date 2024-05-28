<?php

namespace App\Controller;

use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    #[Route('/api/stock/value', name: 'app_stock_value', methods: ['GET'])]
    public function getStockValue(StockRepository $stockRepository): Response
    {
        return new JsonResponse(['value' => $stockRepository->getTotalStockValue()]);
    }

    #[Route('/api/stock/quantity', name: 'app_stock_quantity', methods: ['GET'])]
    public function getStockQuantity(StockRepository $stockRepository): Response
    {
        return new JsonResponse(['quantity' => $stockRepository->getTotalStockQuantity()]);
    }
}