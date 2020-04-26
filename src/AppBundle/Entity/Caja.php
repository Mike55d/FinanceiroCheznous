<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Caja
 *
 * @ORM\Table(name="caja")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CajaRepository")
 */
class Caja
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
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", length=255 , nullable=true)
     */
    private $localidad;

    /**
     * @var string
     *
     * @ORM\Column(name="calle", type="string", length=255 , nullable=true)
     */
    private $calle;

    /**
     * @var string
     *
     * @ORM\Column(name="nCalle", type="string", length=255 , nullable=true)
     */
    private $nCalle;

    /**
     * @var string
     *
     * @ORM\Column(name="barrio", type="string", length=255 , nullable=true)
     */
    private $barrio;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255 , nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=255 , nullable=true)
     */
    private $uf;

    /**
     * @var string
     *
     * @ORM\Column(name="orden", type="string" , length=255)
     */
    private $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string" , length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255 , nullable=true)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ce", type="string", length=255 , nullable=true)
     */
    private $ce;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255 )
     */
    private $tipo;


    /**
     * @var float
     *
     * @ORM\Column(name="saldo", type="float" , scale=2)
     */
    private $saldo;


    /**
     * @var bool
     *
     * @ORM\Column(name="activa", type="boolean" )
     */
    private $activa;

    /**
     * @var bool
     *
     * @ORM\Column(name="negativo", type="boolean" )
     */
    private $negativo;


  

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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Caja
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Caja
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Caja
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Caja
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set orden
     *
     * @param string $orden
     *
     * @return Caja
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return string
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Caja
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set cp
     *
     * @param string $cp
     *
     * @return Caja
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ce
     *
     * @param string $ce
     *
     * @return Caja
     */
    public function setCe($ce)
    {
        $this->ce = $ce;

        return $this;
    }

    /**
     * Get ce
     *
     * @return string
     */
    public function getCe()
    {
        return $this->ce;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return Caja
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     *
     * @return Caja
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set calle
     *
     * @param string $calle
     *
     * @return Caja
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;

        return $this;
    }

    /**
     * Get calle
     *
     * @return string
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set nCalle
     *
     * @param string $nCalle
     *
     * @return Caja
     */
    public function setNCalle($nCalle)
    {
        $this->nCalle = $nCalle;

        return $this;
    }

    /**
     * Get nCalle
     *
     * @return string
     */
    public function getNCalle()
    {
        return $this->nCalle;
    }

    /**
     * Set barrio
     *
     * @param string $barrio
     *
     * @return Caja
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Caja
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set uf
     *
     * @param string $uf
     *
     * @return Caja
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get uf
     *
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Caja
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
     * Set activa
     *
     * @param boolean $activa
     *
     * @return Caja
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * Get activa
     *
     * @return boolean
     */
    public function getActiva()
    {
        return $this->activa;
    }

    /**
     * Set negativo
     *
     * @param boolean $negativo
     *
     * @return Caja
     */
    public function setNegativo($negativo)
    {
        $this->negativo = $negativo;

        return $this;
    }

    /**
     * Get negativo
     *
     * @return boolean
     */
    public function getNegativo()
    {
        return $this->negativo;
    }
}
