<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * responsablesCaja
 *
 * @ORM\Table(name="responsables_caja")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\responsablesCajaRepository")
 */
class responsablesCaja
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
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user", referencedColumnName="id" , onDelete="CASCADE")
    */
    private $user;

    /**
    * @ORM\ManyToOne(targetEntity="Caja")
    * @ORM\JoinColumn(name="caja", referencedColumnName="id" , onDelete="CASCADE")
    */
    private $caja;

    /**
     * @var bool
     *
     * @ORM\Column(name="responsable", type="boolean" , nullable=true)
     */
    private $responsable;



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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return responsablesCaja
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set caja
     *
     * @param \AppBundle\Entity\Caja $caja
     *
     * @return responsablesCaja
     */
    public function setCaja(\AppBundle\Entity\Caja $caja = null)
    {
        $this->caja = $caja;

        return $this;
    }

    /**
     * Get caja
     *
     * @return \AppBundle\Entity\Caja
     */
    public function getCaja()
    {
        return $this->caja;
    }

    /**
     * Set responsable
     *
     * @param boolean $responsable
     *
     * @return responsablesCaja
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return boolean
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
}
