<?php

namespace App\Twig;

use App\Entity\Beer;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Doctrine\Persistence\ObjectManager;

class Custom extends AbstractExtension
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('special', function (Beer $beer) {
                return $this->manager->getRepository(Beer::class)->findByCatTerm('special', $beer->getId());
            }),
            new TwigFunction('normal', function (Beer $beer) {
                return $this->manager->getRepository(Beer::class)->findByCatTerm('normal', $beer->getId());
            }),
           
        ];
    }
}