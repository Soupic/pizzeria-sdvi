<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Service\Dao\PizzaDao;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Count\PizzaService;

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
    public function detailAction(
        PizzaDao        $pizzaDao,
        int             $pizzaId,
        PizzaService    $prix
        ): Response
    {
        // Appel du Dao pour récupéré la pizza cliqué
        $pizza = $pizzaDao->getDetailPizza($pizzaId);

        $prix->calculerPrixPizza($pizza);

        return $this->render("Pizza/detail.html.twig", [
            "pizza" => $pizza,
        ]);
    }
}
