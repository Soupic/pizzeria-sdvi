<?php

namespace App\Service\Count;

use App\Entity\IngredientPizza;

class Prix
{
    public function calculePrixFabricationPizza(float $quantiteIngredient, float $cout)
    {
        // init du compteur de prix
        $prixPizza = 0;
        // Convertion en kilo gramme
        $ingredientKilo = IngredientPizza::convertirGrammeEnKilo($quantiteIngredient);
        // Prix de la pizza
        $prixPizza = $prixPizza + ($cout * $ingredientKilo);
        // Fonction pour arrondir le prix de la pizza
        $prixPizza = round($prixPizza, 2);

        return $prixPizza;
    }
}