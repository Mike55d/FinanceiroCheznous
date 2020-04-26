<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codigo
 *
 * @ORM\Table(name="codigo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CodigoRepository")
 */
class Codigo
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
     * @ORM\Column(name="nro", type="string", length=255)
     */
    private $nro;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

   
    /**
     * @var string
     *
     * @ORM\Column(name="iniciales", type="string", length=255)
     */
    private $iniciales;



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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Codigo
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
     * Set nro
     *
     * @param string $nro
     *
     * @return Codigo
     */
    public function setNro($nro)
    {
        $this->nro = $nro;

        return $this;
    }

    /**
     * Get nro
     *
     * @return string
     */
    public function getNro()
    {
        return $this->nro;
    }

    /**
     * Set iniciales
     *
     * @param string $iniciales
     *
     * @return Codigo
     */
    public function setIniciales($iniciales)
    {
        $this->iniciales = $iniciales;

        return $this;
    }

    /**
     * Get iniciales
     *
     * @return string
     */
    public function getIniciales()
    {
        return $this->iniciales;
    }
}
