<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request, ArticleRepository $articleRepository, TagRepository $tagRepository)
    {

        $articles = [];
        if($request->get('search')) {
            $search = $request->get('search');
            $articles = $articleRepository->findByKeywords(explode(' ', $search));
        }

        $tags = $tagRepository->findAll();
        // affichage (envoi dans lma vue) :
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles,
            'tags' => $tags
        ]);
    }

}
