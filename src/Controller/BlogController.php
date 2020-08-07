<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
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
     * @Route("/blog/{id<[0-9]+>}", name="app_show")
     */
    public function show(Article $article,Request $request,EntityManagerInterface $manager)
    {
        $comment =new Comment();
        $form=$this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreateAt(new \DateTime())
                    ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('app_show',
                ['id' =>$article->getId()
                ]);

        }

        return $this->render('blog/show.html.twig',
            ['article'=>$article,
                'commentForm'=>$form->createView()

            ]);
    }

    /**
     * @Route("/blog/new", name="app_create")
     * @Route("/blog/{id<[0-9]+>}/edit")
     */
    public function form(Article $article=null, Request $request,EntityManagerInterface $manager)
    {
        if (!$article){
            $article=new Article();
        }
/*        $form=$this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();
*/
        $form=$this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()){
            $article-> setCreateAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('app_show',['id' =>$article->getId()]);
        }
        return $this->render('blog/create.html.twig',[
        'formArticle'=>$form->createView(),
         'editMod'=>$article->getId() !==null
    ]);
    }
}
