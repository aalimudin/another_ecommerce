<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserOrder
 *
 * @ORM\Table(name="user_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserOrder
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @ORM\Version
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="OrderAddress")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id", nullable=true)
     */
    private $addressId;

    /**
     * @var int
     *
     * @ORM\Column(name="total_price", type="integer", nullable=true)
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100)
     */
    private $status = "Waiting for approval";

    /**
     * @var string
     *
     * @ORM\Column(name="approval", type="string", length=100)
     */
    private $approval = "Pending";

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return UserOrder
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserOrder
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
     * Set addressId
     *
     * @param integer $addressId
     *
     * @return UserOrder
     */
    public function setAddressId($addressId)
    {
        $this->addressId = $addressId;

        return $this;
    }

    /**
     * Get addressId
     *
     * @return int
     */
    public function getAddressId()
    {
        return $this->addressId;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return UserOrder
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
     * Set status
     *
     * @param string $status
     *
     * @return UserOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set approval
     *
     * @param string $approval
     *
     * @return UserOrder
     */
    public function setApproval($approval)
    {
        $this->approval = $approval;

        return $this;
    }

    /**
     * Get approval
     *
     * @return string
     */
    public function getApproval()
    {
        return $this->approval;
    }
}

