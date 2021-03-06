<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaccion
 *
 * @ORM\Table(name="transaccion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransaccionRepository")
 */
class Transaccion
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
    * @ORM\ManyToOne(targetEntity="Caja")
    * @ORM\JoinColumn(name="caja_envia", referencedColumnName="id" , nullable=true, onDelete="SET NULL")
    */
    private $cajaEnvia;

/**
    * @ORM\ManyToOne(targetEntity="Caja")
    * @ORM\JoinColumn(name="caja_recibe", referencedColumnName="id" , nullable=true, onDelete="SET NULL")
    */
    private $cajaRecibe;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_envia", referencedColumnName="id" , nullable=true , onDelete="SET NULL")
    */
    private $userEnvia;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_aprueba", referencedColumnName="id" , nullable=true , onDelete="SET NULL")
    */
    private $userAprueba;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_solicita", referencedColumnName="id" , nullable=true , onDelete="SET NULL")
    */
    private $userSolicita;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;


    /**
     * @var \DateTime
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
    * @ORM\JoinColumn(name="asociacion", referencedColumnName="id" ,onDelete="SET NULL")
    */

    private $asociacion;

    /**
    * @ORM\ManyToOne(targetEntity="Codigo")
    * @ORM\JoinColumn(name="codigo", referencedColumnName="id" ,onDelete="SET NULL")
    */

    private $codigo;

    /**
    * @ORM\ManyToOne(targetEntity="Fuente")
    * @ORM\JoinColumn(name="fuente", referencedColumnName="id" ,onDelete="SET NULL")
    */
    private $fuente;


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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Transaccion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Transaccion
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
     * @return Transaccion
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
     * Set cajaEnvia
     *
     * @param \AppBundle\Entity\Caja $cajaEnvia
     *
     * @return Transaccion
     */
    public function setCajaEnvia(\AppBundle\Entity\Caja $cajaEnvia = null)
    {
        $this->cajaEnvia = $cajaEnvia;

        return $this;
    }

    /**
     * Get cajaEnvia
     *
     * @return \AppBundle\Entity\Caja
     */
    public function getCajaEnvia()
    {
        return $this->cajaEnvia;
    }

    /**
     * Set cajaRecibe
     *
     * @param \AppBundle\Entity\Caja $cajaRecibe
     *
     * @return Transaccion
     */
    public function setCajaRecibe(\AppBundle\Entity\Caja $cajaRecibe = null)
    {
        $this->cajaRecibe = $cajaRecibe;

        return $this;
    }

    /**
     * Get cajaRecibe
     *
     * @return \AppBundle\Entity\Caja
     */
    public function getCajaRecibe()
    {
        return $this->cajaRecibe;
    }

    /**
     * Set userEnvia
     *
     * @param \AppBundle\Entity\User $userEnvia
     *
     * @return Transaccion
     */
    public function setUserEnvia(\AppBundle\Entity\User $userEnvia = null)
    {
        $this->userEnvia = $userEnvia;

        return $this;
    }

    /**
     * Get userEnvia
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserEnvia()
    {
        return $this->userEnvia;
    }

    /**
     * Set userAprueba
     *
     * @param \AppBundle\Entity\User $userAprueba
     *
     * @return Transaccion
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
     * Set userSolicita
     *
     * @param \AppBundle\Entity\User $userSolicita
     *
     * @return Transaccion
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
     * Set asociacion
     *
     * @param \AppBundle\Entity\Asociacion $asociacion
     *
     * @return Transaccion
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
     * @return Transaccion
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
     * @return Transaccion
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
