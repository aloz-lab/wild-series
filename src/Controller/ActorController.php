<?php


namespace App\Controller;


use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor")
 */
class ActorController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/", name="actor_index", methods={"GET"})
     * @param ActorRepository $actorRepository
     * @return Response
     */
    public function index(ActorRepository $actorRepository): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $actorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="actor_show", methods={"GET"})
     * @param Actor $actor
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        $programs = $actor->getPrograms();

        return $this->render('actor/show.html.twig', [
            'programs' => $programs,
            'actor' => $actor
        ]);
    }

}