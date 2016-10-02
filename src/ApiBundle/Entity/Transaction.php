<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @author Roman Belousov <romanandreyvich@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="transaction")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="float"
     * )
     */
    protected $asset;
    /**
     * @ORM\ManyToOne(targetEntity="TransactionCategory", inversedBy="transactions")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $category;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="transactions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     * @Serializer\Exclude
     */
    protected $user;
    /**
     * @ORM\Column(name="time", type="datetime")
     */
    protected $time;

    public function __construct()
    {
        $this->time = new \DateTime();
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
     * Set asset
     *
     * @param float $asset
     *
     * @return Transaction
     */
    public function setAsset($asset)
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * Get asset
     *
     * @return float
     */
    public function getAsset()
    {
        return $this->asset;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Transaction
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set category
     *
     * @param TransactionCategory $category
     *
     * @return Transaction
     */
    public function setCategory(TransactionCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return TransactionCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Transaction
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
}
