<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\MessageFormType;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="article_oldindex")
     */
    public function oldindex(ArticleRepository $articleRepository): Response
    {
        return $this->render('blog/oldindex.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/blog/{article}", name="article", methods={"GET"})
     */
    public function article(Request $request, Article $article,ArticleRepository $articleRepository,MessageRepository $messageRepository): Response
    {
//        $articleMessages = $articleRepository->findBy(['message'=>$article]);
        $articleMessages = $article->getMessages();
        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'messages' => $articleMessages,
        ]);
    }
}
