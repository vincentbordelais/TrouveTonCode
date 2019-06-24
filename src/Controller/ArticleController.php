<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/article/list", name="article_list")
     */
    public function getList(ArticleRepository $articleRepo)
    {
        $articles = $articleRepo->findAll();
        return $this->render('admin/article_list.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/admin/article/create", name="article_create")
     */
    public function createArticle(Request $request, TagRepository $tagRepository)
    {
        $article = new Article();
        $article->setCreationDate(new\DateTime());

        $form = $this->createForm(ArticleType::class, $article);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $tagList = $article->getTagsList();
            $tagNames = explode(',', $tagList); // scinde la chaîne

            /*
             * Récupération du Manager de Doctrine
             * Le EntityManager ($em) est une classe qui sait comment persister d'autres classes.
             * (Effectuer des opérations CRUD sur nos Entités).
             */
            $em = $this->getDoctrine()->getManager();

            foreach ($tagNames as $tagName) {
                $tag = $tagRepository->findOneBy(['name'=>trim($tagName)]);

                if(!$tag){
                    $tag = new Tag();
                    $tag->setName(trim($tagName)); //retire les espaces
                }

                $article->addTag($tag);

                # Insertion dans le BDD (EntityManager $em)
                $em->persist($tag);
            }
            $article->setUser($this->getUser());

            # Insertion dans le BDD (EntityManager $em)
            $em->persist($article);
            $em->flush();

            #Notification
            $this->addFlash('notice', 'Felicitation, vous pouvez vous connecter!');

            #Redirection
            return $this->redirectToRoute('home');

        }


        return $this->render('admin/article_create.html.twig', [

            'form' => $form->createView()
        ]);
    }



}




