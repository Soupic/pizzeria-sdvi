<?php

declare(strict_types = 1);


namespace App\Controller;

use App\Service\Dao\PizzeriaDao;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Count\Prix;

/**
 * Class PizzeriaController
 * @package App\Controller
 */
class PizzeriaController extends AbstractController
{
    /**
     * @param PizzeriaDao $pizzeriaDao
     * @Route("/pizzerias")
     * @return Response
     */
    public function listeAction(PizzeriaDao $pizzeriaDao): Response
    {
        // récupération des différentes pizzéria de l'application
        $pizzerias = $pizzeriaDao->getAllPizzerias();

        return $this->render("Pizzeria/liste.html.twig", [
            "pizzerias" => $pizzerias,
        ]);
    }

    /**
     * @param int $pizzeriaId
     * @Route(
     *     "/pizzerias/carte-{pizzeriaId}",
     *     requirements={"pizzeriaId": "\d+"}
     * )
     * @return Response
     */
    public function detailAction(int $pizzeriaId, PizzeriaDao $pizzeriaDao, Prix $cout): Response
    {   
        // Appel du DAO pour récupéré la carte de la pizzeria
        $pizzeriaCarte = $pizzeriaDao->getCartePizzeria($pizzeriaId);

        $pizzas = $pizzeriaCarte->getPizzas();
        $prixPizza = 0;
        $listePizza = [];
        foreach ($pizzas as $pizza) {
            $ingredients = $pizza->getQuantiteIngredients();
            foreach ($ingredients as $ingredient) {
                $quantiteIngredient = $ingredient->getQuantite();
            }
            $prixPizza += $cout->calculePrixPizza($quantiteIngredient, $ingredient->getIngredient()->getCout());
            $prix = $prixPizza + $pizzeriaCarte->getMarge();
            $listePizza[] = [$pizza->getNom(), $prix];
        }

        return $this->render("Pizzeria/carte.html.twig", [
            "pizzeria" => $pizzeriaCarte,
            "liste_pizza" => $listePizza,
        ]);
    }
}
