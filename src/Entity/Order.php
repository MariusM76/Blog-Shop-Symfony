<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $delivery;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity=cart::class )
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity=adress::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $adress;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="assignedOrder", orphanRemoval=true)
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
//        $this->orderitems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(string $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(string $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCart(): ?cart
    {
        return $this->cart;
    }

    public function setCart(cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getAdress(): ?adress
    {
        return $this->adress;
    }

    public function setAdress(adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * @return Collection<int, Orderitem>
     */
    public function getOrderitems(): Collection
    {
        return $this->orderitems;
    }

    public function addOrderitem(Orderitem $orderitem): self
    {
        if (!$this->orderitems->contains($orderitem)) {
            $this->orderitems[] = $orderitem;
            $orderitem->setAssignedOrder($this);
        }

        return $this;
    }

    public function removeOrderitem(Orderitem $orderitem): self
    {
        if ($this->orderitems->removeElement($orderitem)) {
            // set the owning side to null (unless already changed)
            if ($orderitem->getAssignedOrder() === $this) {
                $orderitem->setAssignedOrder(null);
            }
        }

        return $this;
    }
}
