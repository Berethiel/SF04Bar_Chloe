<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Entity\Client;
use App\Entity\Statistic;
use App\Form\StatFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    /**
     * @Route("/statistic", name="statistic")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Statistic::class);
        $stats = $repository->findAll();
        $repository = $this->getDoctrine()->getRepository(Beer::class);
        $beers = $repository->findAll();

        return $this->render('statistic/index.html.twig', [
            'controller_name' => 'StatisticController',
            'stats' => $stats,
            'beers' => $beers
        ]);
    }
}
