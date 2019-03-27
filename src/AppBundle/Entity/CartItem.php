<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartItem
 *
 * @ORM\Table(name="cart_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CartItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="total_price", type="integer")
     */
    private $totalPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @ORM\Version
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Cart")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    private $cartId;

    public function timestampOnPrePersist()
    {
        $this->setCreatedAt = date('Y-m-d H:i:s');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return CartItem
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CartItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return CartItem
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CartItem
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set cartId
     *
     * @param integer $cartId
     *
     * @return CartItem
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;

        return $this;
    }

    /**
     * Get cartId
     *
     * @return int
     */
    public function getCartId()
    {
        return $this->cartId;
    }
}

