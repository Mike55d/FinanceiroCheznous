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
    * @ORM\JoinColumn(name="caja_solicita", referencedColumnName="id" , onDelete="SET NULL")
    */
    private $cajaSolicita;

    /**
    * @ORM\ManyToOne(targetEntity="Caja")
    * @ORM\JoinColumn(name="caja_solicitada", referencedColumnName="id" , onDelete="SET NULL")
    */
    private $cajaSolicitada;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_solicita", referencedColumnName="id" , nullable=true , onDelete="SET NULL")
    */
    private $userSolicita;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_aprueba", referencedColumnName="id" , nullable=true , onDelete="SET NULL")
    */
    private $userAprueba;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var decimal
     *
     * @ORM\Column(name="monto", type="decimal" , scale=2)
     */
    private $monto;

    /**
    * @ORM\ManyToOne(targetEntity="Asociacion")
    * @ORM\JoinColumn(name="asociacion", referencedColumnName="id")
    */

    private $asociacion;

    /**
    * @ORM\ManyToOne(targetEntity="Codigo")
    * @ORM\JoinColumn(name="codigo", referencedColumnName="id")
    */

    private $codigo;

    /**
    * @ORM\ManyToOne(targetEntity="Fuente")
    * @ORM\JoinColumn(name="fuente", referencedColumnName="id")
    */
    private $fuente;


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

    /**
     * Set asociacion
     *
     * @param \AppBundle\Entity\Asociacion $asociacion
     *
     * @return Solicitud
     */
    public function setAsociacion(\AppBundle\Entity\Asociacion $asociacion = null)
    {
        $this->asociacion = $asociacion;

        return $this;
    }

    /**
     * Get asociacion
     *
     * @return \AppBundle\Entity\Asociacion
     */
    public function getAsociacion()
    {
        return $this->asociacion;
    }

    /**
     * Set codigo
     *
     * @param \AppBundle\Entity\Codigo $codigo
     *
     * @return Solicitud
     */
    public function setCodigo(\AppBundle\Entity\Codigo $codigo = null)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return \AppBundle\Entity\Codigo
     */
    public function getCodigo()
    {
        return $this->codigo;
    }


    /**
     * Set fuente
     *
     * @param \AppBundle\Entity\Fuente $fuente
     *
     * @return Solicitud
     */
    public function setFuente(\AppBundle\Entity\Fuente $fuente = null)
    {
        $this->fuente = $fuente;

        return $this;
    }

    /**
     * Get fuente
     *
     * @return \AppBundle\Entity\Fuente
     */
    public function getFuente()
    {
        return $this->fuente;
    }
}
