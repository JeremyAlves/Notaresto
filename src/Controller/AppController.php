<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Restaurant;
use App\Entity\Review;
use App\Repository\RestaurantRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
/**
 * @Route("/", name="app_index", methods={"GET"})
 */
public function index()
{

    /**
     * On récupère les données de notre nouvelle méthode
     */
    $tenBestRestaurantsId = $this->getDoctrine()->getRepository(Review::class)->findBestTenRatings();

    $tenBestRestaurants = array_map(function($data) {
        return $this->getDoctrine()->getRepository(Restaurant::class)->find($data['restaurantId']);
    }, $tenBestRestaurantsId);

    /**
     * On prépare le futur array d'objets Restaurant
     */
    $tenBestRestaurants = [];

    /**
     * On boucle sur le tableau de données retourné par le ReviewRepository
     */
    foreach($tenBestRestaurantsId as $data) {
        // Pour chaque élément on prend le `restaurantId` et on cherche l'objet Restaurant grace au RestaurantRepository :
        $tenBestRestaurants[] = $this->getDoctrine()->getRepository(Restaurant::class)->find($data['restaurantId']);
    }

    return $this->render('app/index.html.twig', [
        // Cette fois, on envoie à Twig notre nouveau tableau
        'restaurants' => $tenBestRestaurants,
    ]);
}

// AFFICHER LES 10 DERNIERS RESTAURANTS AJOUTES
    // /**
    //  * @Route("/", name="app")
    //  */
    // public function index(RestaurantRepository $restaurantRepository) : Response {
    //     $restaurants = $restaurantRepository->findLastTenElements();
    //     return $this->render('app/index.html.twig', [
    //         'restaurants' => $restaurants
    //     ]);
    // }

/**
* @Route("/search", name="app_search", methods={"GET"})
* @param Request $request
*/
public function search(HttpFoundationRequest $request) {
    // On récupère l'input de recherche du formulaire, le name=zipcode
    $searchZipcode = $request->query->get('zipcode');

    // On recherche une ville par son code postal
    $city = $this->getDoctrine()->getRepository(City::class)->findOneBy(["zipcode" => $searchZipcode]);


    // Si une ville est trouvée
    if ($city) {

        $restaurants = $city->getRestaurants();

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }

    // Sinon, on redirige en page d'accueil
    return $this->redirectToRoute("app_index");
}
}
