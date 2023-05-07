<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageFormType;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
//    /**
//     * @Route("/message", name="app_message")
//     */
//    public function index(): Response
//    {
//        return $this->render('message/index.html.twig', [
//            'controller_name' => 'MessageController',
//        ]);
//    }

    /**
     * @Route("/message/", name="newMessage", methods={"GET","POST"})
     */
    public function newMessage(Request $request,ArticleRepository $articleRepository, MessageRepository $messageRepository)
    {
        $newMessage = new Message();
        $writer = $request->request->get('writer');
        $message = $request->request->get('message');
        $email = $request->request->get('email');
        $articleId = $request->request->get('article');
        $article = $articleRepository->findBy(['id'=>$articleId]);
        $article = $article[0];
        $newMessage->setWriter($writer);
        $newMessage->setMessage($message);
        $newMessage->setArticle($article);
        if ($email){
            $newMessage->setEmail($email);
        }
        $artMessages = $messageRepository->findBy(['article'=>$articleId]);
//        dd($artmessages);
        if ($newMessage->getMessage()!=null){
            $messageRepository->add($newMessage);
        }

        return $this->redirectToRoute('article', [
            'messages'=>$artMessages,
            'article' => $articleId,
        ]);
    }
}
