<?php

namespace ApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Roman Belousov <romanandreyvich@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @Assert\NotBlank()
     */
    protected $username;
    /**
     * @Assert\NotBlank()
     */
    protected $email;
    /**
     * @ORM\ManyToOne(targetEntity="Family", inversedBy="users")
     * @ORM\JoinColumn(name="family_id", referencedColumnName="id", nullable=true)
     */
    protected $family;
    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="user")
     */
    protected $transactions;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set family
     *
     * @param Family $family
     *
     * @return User
     */
    public function setFamily(Family $family = null)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return Family
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return User
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
