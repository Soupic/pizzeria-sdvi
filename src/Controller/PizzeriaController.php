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
        // Récupération de la carte de la pizzeria
        $pizzas = $pizzeriaCarte->getPizzas();
        // Init du prix
        $prixPizza = 0;
        // init d'un tableau pour stocker le nom de la pizza + son prix
        $listePizza = [];
        // Parcours des pizzas
        foreach ($pizzas as $pizza) {
            // Récupération de la quantité d'ingrédients
            $ingredients = $pizza->getQuantiteIngredients();
            foreach ($ingredients as $ingredient) {
                // Récupération de la quantité d'un ingrédient
                $quantiteIngredient = $ingredient->getQuantite();
            }
            // Calcule du coup de fabrication de la pizza
            $prixPizza += $cout->calculePrixFabricationPizza($quantiteIngredient, $ingredient->getIngredient()->getCout());
            // Ajout de la mage de la pizzeria
            $prix = $prixPizza + $pizzeriaCarte->getMarge();
            $listePizza[] = [
                "nom" => $pizza->getNom(),
                "prix" => $prix,
            ];
        }

        return $this->render("Pizzeria/carte.html.twig", [
            "pizzeria" => $pizzeriaCarte,
            "liste_pizza" => $listePizza,
        ]);
    }
}
