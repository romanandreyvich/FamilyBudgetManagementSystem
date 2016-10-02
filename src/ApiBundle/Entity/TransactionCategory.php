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
 * @ORM\Table(name="transaction_category")
 */
class TransactionCategory
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
     * @ORM\ManyToOne(targetEntity="TransactionType", inversedBy="categories")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $type;
    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="category", cascade={"persist", "remove"})
     * @Serializer\Exclude
     */
    protected $transactions;
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
     * @return TransactionCategory
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
     * Set type
     *
     * @param TransactionType $type
     *
     * @return TransactionCategory
     */
    public function setType(TransactionType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return TransactionType
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return TransactionCategory
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param Transaction $transaction
     */
    public function removeTransaction(Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }
}
