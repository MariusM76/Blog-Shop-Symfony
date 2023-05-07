<?php

namespace App\Entity;

use App\Repository\ReplyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReplyRepository::class)
 */
class Reply
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $writer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="replies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pMessage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWriter(): ?string
    {
        return $this->writer;
    }

    public function setWriter(string $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPMessage(): ?Message
    {
        return $this->pMessage;
    }

    public function setPMessage(?Message $pMessage): self
    {
        $this->pMessage = $pMessage;

        return $this;
    }
}
