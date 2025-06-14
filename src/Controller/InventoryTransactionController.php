<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class InventoryTransactionController extends AbstractController
{

    #[Route('/inventory/transaction', name: 'app_inventory_transaction')]
    public function index(): Response
    {
        return $this->render('inventory_transaction/index.html.twig', [
            'controller_name' => 'InventoryTransactionController',
        ]);
    }

}
