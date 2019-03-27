<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\CartItem;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Cart
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
     * @ORM\ManyToOne(targetEntity = "User")
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id")
     */
    private $userId;

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
     * @ORM\Column(name="total_price", type="integer", nullable=true)
     */
    private $totalPrice = 0;

    /** 
     *  @ORM\PrePersist 
    */
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Cart
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Cart
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
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return Cart
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
}

