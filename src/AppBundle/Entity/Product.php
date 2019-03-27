<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")     
     * */
    private $categoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

      /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
     * @var int
     *
     * @ORM\Column(name="qty_hold", type="integer")
     */
    private $qtyHold;

    /**
     * @var int
     *
     * @ORM\Column(name="available_qty", type="integer")
     */
    private $availableQty;


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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return Product
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

     /**
     * Set price
     *
     * @param integer $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set qtyHold
     *
     * @param integer $qtyHold
     *
     * @return Product
     */
    public function setQtyHold($qtyHold)
    {
        $this->qtyHold = $qtyHold;

        return $this;
    }

    /**
     * Get qtyHold
     *
     * @return int
     */
    public function getQtyHold()
    {
        return $this->qtyHold;
    }

    /**
     * Set availableQty
     *
     * @param integer $availableQty
     *
     * @return Product
     */
    public function setAvailableQty($availableQty)
    {
        $this->availableQty = $availableQty;

        return $this;
    }

    /**
     * Get availableQty
     *
     * @return int
     */
    public function getAvailableQty()
    {
        return $this->availableQty;
    }
}

