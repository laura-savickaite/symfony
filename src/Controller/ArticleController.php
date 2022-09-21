<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

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

    #[Route('/articles', name: 'app_addarticle')]
    public function showArticle(EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response {

        $repository = $doctrine->getRepository(Article::class);
        $articles = $repository->findAll();

        // dd($article);

        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

}
