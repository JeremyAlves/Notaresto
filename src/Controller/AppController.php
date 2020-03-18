<?php

namespace App\Controller;

use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
    public function index(RestaurantRepository $restaurantRepository) : Response {
        $restaurants = $restaurantRepository->findLastTenElements();
        return $this->render('app/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }
}
