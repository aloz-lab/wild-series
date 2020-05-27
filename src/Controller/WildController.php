<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
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
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table.');
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs
        ]);
    }

    /**
     * @param string $slug The slugger
     * @Route("/wild/show/{slug}",
     *     requirements={"slug"="^[0-9a-z-]+$"},
     *     defaults={"slug"=null},
     *     name="wild_show")
     * @return Response
     */
    public function show(?string $slug) :Response
    {
        if (!$slug) {
            throw $this->createNotFoundException(
                'No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }
        $seasons = $program->getSeasons();
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'seasons' => $seasons
        ]);
    }

    /**
     * @Route("/wild/category/{categoryName}", name="show_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName) :Response
    {
        if (!$categoryName) {
            throw $this->createNotFoundException(
                'No category has been sent to find a program in program\'s table.');
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'desc'], 3);

        if (!$category) {
            throw $this->createNotFoundException(
                'No program in category '.$categoryName.', found in program\'s table.'
            );
        }

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'categoryName'  => $categoryName,
        ]);
    }

    /**
     * @param string $slug2 The slugger
     * @Route("/wild/show/{slug2}",
     *     requirements={"slug2"="^[0-9a-z-]+$"},
     *     defaults={"slug2"=null},
     *     name="wild_show")
     * @return Response
     */
    /*public function showByProgram(?string $slug2) :Response
    {
        if (!$slug2) {
            throw $this->createNotFoundException(
                'No slug has been sent to find a program in program\'s table.');
        }
        $slug2 = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug2)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug2)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug2.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug2,
        ]);
    }*/

    /**
     * @Route("wild/showBySeason/{id}", name="wild_season")
     * @param $id
     * @return Response
     */
    public function showBySeason(int $id) :Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);
        $program = $season->getProgram();
        $episodes = $season->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'season'  => $season,
            'episodes'  => $episodes
        ]);
    }

    /**
     * @Route("wild/episode/{id}", name="wild_episode")
     * @return Response
     */
    public function showEpisode(Episode $episode) :Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'program' => $program,
            'season'  => $season,
            'episode'  => $episode
        ]);
    }

}