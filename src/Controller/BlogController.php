<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(ArticleRepository $repo)
    {
      //  $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles= $repo ->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=>$articles
        ]);
    }
    /**
     * @Route("/", name="app_home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/new", name="app_create")
     */
    public function create(Request $request,EntityManagerInterface $manager)
    {
       // dd($manager);
        $article=new Article();
        $form=$this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();
        return $this->render('blog/create.html.twig',[
        'formArticle'=>$form->createView()
    ]);
    }
    /**
     * @Route("/blog/{id<[0-9]+>}", name="app_show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig',
            ['article'=>$article

            ]);
    }
}
