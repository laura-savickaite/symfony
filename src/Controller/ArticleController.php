<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comments;
use App\Form\ArticleFormType;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ArticleController extends AbstractController {

    //!!! check l'upload d'images
    #[Route('/add', name: 'app_addarticle')]
    public function addAnArtcile(Request $request, EntityManagerInterface $entityManager): Response {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();

        // dd(intval($this->getUser()->getId()));
        $article = new Article();
        $article -> setAuthor($user);
        $addArticle = $this->createForm(ArticleFormType::class, $article);
        $addArticle ->handleRequest($request);

        if ($addArticle->isSubmitted() && $addArticle->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render('article/add.html.twig', [
            'addArticle' => $addArticle->createView(),
        ]);
    }

    #[Route('/articles', name: 'app_articles')]
    public function showArticle(EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response {

        $repository = $entityManager -> getRepository(Article::class);
        // semblable à :
        // $repository = $doctrine->getRepository(Article::class);
        $articles = $repository->findAll();


        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/{id}', name: 'app_singlearticle')]
    public function readArticle(Request $request, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response {

        //afficher l'article
        $id = $request->get('id');
        $repository = $entityManager -> getRepository(Article::class);
        $article = $repository->find($id);
        // dd($article);

        //ajoute un commentaire
        $user = $this->getUser();
        // dd(new \DateTime());

        $comment = new Comments();
        $comment -> setUser($user);
        $comment -> setDate(new \DateTime());
        $comment -> setArticle($article);
        $addComment = $this->createForm(CommentFormType::class, $comment);
        $addComment -> handleRequest($request);

        if($addComment -> isSubmitted() && $addComment -> isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush($comment);
        }


        return $this->render('article/article.html.twig', [
            'article' => $article,
            'addComment' => $addComment->createView(),
        ]);
    }

}
