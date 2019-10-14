<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitud
 *
 * @ORM\Table(name="solicitud")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SolicitudRepository")
 */
class Solicitud
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
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
    * @ORM\ManyToOne(targetEntity="Caja")
    * @ORM\JoinColumn(name="caja_solicita", referencedColumnName="id")
    */
    private $cajaSolicita;

    /**
    * @ORM\ManyToOne(targetEntity="Caja")
    * @ORM\JoinColumn(name="caja_solicitada", referencedColumnName="id")
    */
    private $cajaSolicitada;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_solicita", referencedColumnName="id" , nullable=true)
    */
    private $userSolicita;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_aprueba", referencedColumnName="id" , nullable=true)
    */
    private $userAprueba;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float" , scale=2)
     */
    private $monto;


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
     * Set status
     *
     * @param string $status
     *
     * @return Solicitud
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
     * Set cajaSolicita
     *
     * @param \AppBundle\Entity\Caja $cajaSolicita
     *
     * @return Solicitud
     */
    public function setCajaSolicita(\AppBundle\Entity\Caja $cajaSolicita = null)
    {
        $this->cajaSolicita = $cajaSolicita;

        return $this;
    }

    /**
     * Get cajaSolicita
     *
     * @return \AppBundle\Entity\Caja
     */
    public function getCajaSolicita()
    {
        return $this->cajaSolicita;
    }

    /**
     * Set cajaSolicitada
     *
     * @param \AppBundle\Entity\Caja $cajaSolicitada
     *
     * @return Solicitud
     */
    public function setCajaSolicitada(\AppBundle\Entity\Caja $cajaSolicitada = null)
    {
        $this->cajaSolicitada = $cajaSolicitada;

        return $this;
    }

    /**
     * Get cajaSolicitada
     *
     * @return \AppBundle\Entity\Caja
     */
    public function getCajaSolicitada()
    {
        return $this->cajaSolicitada;
    }

    /**
     * Set userSolicita
     *
     * @param \AppBundle\Entity\User $userSolicita
     *
     * @return Solicitud
     */
    public function setUserSolicita(\AppBundle\Entity\User $userSolicita = null)
    {
        $this->userSolicita = $userSolicita;

        return $this;
    }

    /**
     * Get userSolicita
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserSolicita()
    {
        return $this->userSolicita;
    }

    /**
     * Set userAprueba
     *
     * @param \AppBundle\Entity\User $userAprueba
     *
     * @return Solicitud
     */
    public function setUserAprueba(\AppBundle\Entity\User $userAprueba = null)
    {
        $this->userAprueba = $userAprueba;

        return $this;
    }

    /**
     * Get userAprueba
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserAprueba()
    {
        return $this->userAprueba;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Solicitud
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set monto
     *
     * @param integer $monto
     *
     * @return Solicitud
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return integer
     */
    public function getMonto()
    {
        return $this->monto;
    }
}
