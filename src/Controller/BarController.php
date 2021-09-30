<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Country;
use App\Entity\Statistic;
use App\Form\StatFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BarController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);
        $beers = $repository->findAll();
        
        return $this->render('bar/index.html.twig', [
            'controller_name' => 'BarController',
            'beers' => $beers
        ]);
    }

    /**
     * @Route("/country/{id}", name="show_country_beer")
     */
    public function showBeerByCountry(Country $country): Response
    {
        // dump($country); die;

        return $this->render('country/index.html.twig', [
          'beers' => $country->getBeers() ?? [],
          'title' => $country->getName()
        ]);
    }

    /**
     * @Route("/category/{id}", name="show_beer_category")
     */
    public function category(Category $category){

        return $this->render('category/index.html.twig', [
            'beers' => $category->getBeers() ?? [],
            'title' => $category->getName()
        ]);
    }


    /**
     * @Route("/menu", name="menu")
     */
    public function mainMenu(string $routeName, int $catId = null): Response
    {
        // on fait une instance de Doctrine 
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['term' => 'normal']);

        return $this->render('partials/menu.html.twig', [
            'route_name' => $routeName,
            'category_id' => $catId,
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/show_beer/{id_beer}/{id_client}", name="show_beer")
     *
     * @ParamConverter("beer", options={"mapping": {"id_beer" : "id"}})
     * @ParamConverter("client", options={"mapping": {"id_client"   : "id"}})
     *
     */
    public function showBeer(Request $request,Beer $beer,Client $client = null): Response
    {
        //$test = $request->get('id_beer');
        //dump($test); die;
        
        $stat = new Statistic();

        $form= $this->createForm(StatFormType::class,$stat);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            /**
             * @var Statistic $note
             */
            $note = $form ->getData();
            $note->setBeer($beer);
            $note->setClient($client);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();

            //redirection vers la page d'acceuil
            return $this->redirectToRoute('statistic');
        }
        
        return $this->render('bar/stat.html.twig', [
            'controller_name' => 'StatisticController',
            'beer' => $beer,
            'form' => $form -> createView(),
        ]);
    }
}
