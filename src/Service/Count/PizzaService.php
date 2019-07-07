<?php

namespace App\Service\Count;

use App\Entity\IngredientPizza;
use App\Entity\Pizza;

class PizzaService
{
    /**
     * @param Pizza $pizza
     * @return void
     */
    public function calculerPrixPizza(Pizza $pizza)
    {
        $ingredientsPizza = $pizza->getQuantiteIngredients();
        $prixFabrication = 0;

        foreach ($ingredientsPizza as $ingredientPizza) {
            $quantiteIngredient = $ingredientPizza->getQuantite();
            $ingredientKilo = IngredientPizza::convertirGrammeEnKilo($quantiteIngredient);
            $coutIngredient = $ingredientPizza->getIngredient()->getCout();

            $prixFabrication += $ingredientKilo * $coutIngredient;
            $prixFabrication = round($prixFabrication, 2);
        }

        $pizza->setPrixPizza($prixFabrication);
    }
}