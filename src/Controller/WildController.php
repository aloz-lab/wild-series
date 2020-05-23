<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}",
     *     requirements={"slug"="[0-9a-z\-]+"},
     *     defaults={"slug"=null},
     *     name="wild_show")
     */
    public function show($slug) :Response
    {
        $slugRepl = str_replace("-", " ", $slug);
        $slugMaj = ucwords($slugRepl);
        return $this->render('wild/show.html.twig',
            ['slug' => $slugMaj]);
    }

}