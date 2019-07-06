<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Service\Dao\PizzaDao;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Count\Prix;

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
    public function detailAction(PizzaDao $pizzaDao, int $pizzaId, Prix $prix): Response
    {
        // Appel du Dao pour récupéré la pizza cliqué
        $pizza = $pizzaDao->getDetailPizza($pizzaId);

        // init du compteur de prix
        $prixPizza = 0;
        // Boucle pour récupéré les ingrédients qui compose la pizza
        $nomIngredientPizza = [];
        foreach ($pizza->getQuantiteIngredients() as $ingredientPizza) {
            // Récupération de la quantité d'ingrédient
            $quantiteIngredient = $ingredientPizza->getQuantite();
            $prixPizza += $prix->calculePrixPizza( $quantiteIngredient, $ingredientPizza->getIngredient()->getCout());
            $nomIngredientsPizza[] = $ingredientPizza->getIngredient()->getNom();
        };

        return $this->render("Pizza/detail.html.twig", [
            "pizza" => $pizza,
            "prix" => $prixPizza,
            "nomIngredients" => $nomIngredientsPizza,
        ]);
    }
}
