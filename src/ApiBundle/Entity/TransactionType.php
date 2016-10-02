<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @author Roman Belousov <romanandreyvich@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="transaction_type")
 */
class TransactionType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="TransactionCategory", mappedBy="type", cascade={"persist", "remove"})
     * @Serializer\Exclude
     */
    protected $categories;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return TransactionType
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
     * Add category
     *
     * @param TransactionCategory $category
     *
     * @return TransactionType
     */
    public function addCategory(TransactionCategory $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param TransactionCategory $category
     */
    public function removeCategory(TransactionCategory $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
