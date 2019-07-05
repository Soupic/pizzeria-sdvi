<?php

declare(strict_types = 1);


namespace App\Controller;

use App\Service\Dao\PizzeriaDao;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function detailAction(int $pizzeriaId, PizzeriaDao $pizzeriaDao): Response
    {
        $pizzeriaCarte = $pizzeriaDao->getCartePizzeria($pizzeriaId);

        dump( $pizzeriaCarte);

        return $this->render("Pizzeria/carte.html.twig", [
            "pizzeria" => $pizzeriaCarte,
        ]);
    }
}
