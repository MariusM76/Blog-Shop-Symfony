<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Reply;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use App\Repository\ReplyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReplyController extends AbstractController
{
    /**
     * @Route("/reply", name="newReply")
     */
    public function reply(Request $request,ArticleRepository $articleRepository, MessageRepository $messageRepository,ReplyRepository $replyRepository): Response
    {
        $newReply = new Reply();
        $writer = $request->request->get('writer');
        $message = $request->request->get('message');
        $email = $request->request->get('email');
        $pMessageId = $request->request->get('messageId');
        $articleId = $request->request->get('article');
        $pMessage1 = $messageRepository->findBy(['id'=>$pMessageId]);
        $pMessage = $pMessage1[0];

        $newReply->setWriter($writer);
        $newReply->setMessage($message);
        $newReply->setPMessage($pMessage);

        if ($email){
            $newReply->setEmail($email);
        }
        $artMessages = $messageRepository->findBy(['article'=>$articleId]);
//        $messagesReplies = $replyRepository->findBy(['pMessage'=>$pMessageId]);
        $messagesReplies = new Message($pMessageId);
        $messagesReplies = $messagesReplies->getReplies();
//        dd($artmessages);
        if ($newReply->getMessage()!=null){
            $replyRepository->add($newReply);
        }


        return $this->redirectToRoute('article', [
            'messages'=>$artMessages,
            'article' => $articleId,
            'replies'=>$messagesReplies,
        ]);
    }
}
