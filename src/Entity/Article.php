<?php

namespace App\Entity;

use App\Controller\UserController;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

//    /**
////     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="name")
////     * @ORM\JoinColumn
//     * @ORM\Column(type="string")
//     */
//    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="name")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="article", orphanRemoval=true)
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Article
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    public function getDateToString($date)
    {
        return $date->format('d.M.Y H:i:s');
    }

    public function getAuthor()
    {
        return $this->author->getName();
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


/**
 * @return Collection<int, Message>
 */
public function getMessages(): Collection
{
//    dd($this->messages);
    return $this->messages;
}

public function addMessage(Message $message): self
{
    if (!$this->messages->contains($message)) {
        $this->messages[] = $message;
        $message->setArticle($this);
    }

    return $this;
}

public function removeMessage(Message $message): self
{
    if ($this->messages->removeElement($message)) {
        // set the owning side to null (unless already changed)
        if ($message->getArticle() === $this) {
            $message->setArticle(null);
        }
    }

    return $this;
}


}
