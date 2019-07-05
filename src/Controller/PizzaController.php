<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Service\Dao\PizzaDao;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\IngredientPizza;

/**
 * Class PizzaController
 * @package App\Controller
 */
class PizzaController extends AbstractController
{
    /**
     * @param PizzaDao $pizzaDao
     * @Route("/pizzas")
     * @return Response
     */
    public function listeAction(PizzaDao $pizzaDao): Response
    {
        // récupération des différentes pizzas
        $pizzas = $pizzaDao->getAllPizzas();

        return $this->render("Pizza/liste.html.twig", [
            "pizzas" => $pizzas,
        ]);
    }

    /**
     * @param int $pizzaId
     * @Route(
     *     "/pizzas/detail-{pizzaId}",
     *     requirements={"pizzaId": "\d+"}
     * )
     * @return Response
     */
    public function detailAction(PizzaDao $pizzaDao, int $pizzaId): Response
    {
        // Appel du Dao pour récupéré la pizza cliqué
        $pizza = $pizzaDao->getDetailPizza($pizzaId);

        // init du compteur de prix
        $prixPizza = 0;
        // Boucle pour récupéré les ingrédients qui compose la pizza
        foreach ($pizza->getQuantiteIngredients() as $ingredientPizza) {
            // Récupération de la quantité d'ingrédient
            $quantiteIngredient = $ingredientPizza->getQuantite();
            // Convertion en kilo gramme
            $ingredientKilo = IngredientPizza::convertirGrammeEnKilo($quantiteIngredient);
            // Prix de la pizza
            $prixPizza = $prixPizza + ($ingredientPizza->getIngredient()->getCout() * $ingredientKilo);
        };
        // Fonction pour arrondir le prix de la pizza
        $prixPizza = round($prixPizza, 2);

        return $this->render("Pizza/detail.html.twig", [
            "pizza" => $pizza,
            "prix" => $prixPizza,
        ]);
    }
}
