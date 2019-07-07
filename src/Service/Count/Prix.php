<?php

namespace App\Service\Count;

use App\Entity\IngredientPizza;
use App\Entity\Pizza;
use App\Entity\Pizzeria;

class Prix
{
    /**
     * @param Pizza $pizza
     * @param Pizzeria $pizzeria
     * @return void
     */
    public function calculerPrixPizza(Pizza $pizza, Pizzeria $pizzeria)
    {
        $marge = $pizzeria->getMarge();
        $ingredientsPizza = $pizza->getQuantiteIngredients();
        $prixFabrication = 0;

        foreach ($ingredientsPizza as $ingredientPizza) {
            $quantiteIngredient = $ingredientPizza->getQuantite();
            $ingredientKilo = IngredientPizza::convertirGrammeEnKilo($quantiteIngredient);
            $coutIngredient = $ingredientPizza->getIngredient()->getCout();

            $prixFabrication += $ingredientKilo * $coutIngredient;
        }

        $prixPizza = round( $prixFabrication + $marge, 2);

        $pizza->setPrixPizza($prixPizza);
    }
}