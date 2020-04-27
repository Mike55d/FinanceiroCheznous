<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FuentesCodigos
 *
 * @ORM\Table(name="fuentes_codigos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FuentesCodigosRepository")
 */
class FuentesCodigos
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
    * @ORM\ManyToOne(targetEntity="Codigo")
    * @ORM\JoinColumn(name="codigo", referencedColumnName="id" ,onDelete="CASCADE")
    */
    private $codigo;


    /**
    * @ORM\ManyToOne(targetEntity="Fuente")
    * @ORM\JoinColumn(name="fuente", referencedColumnName="id" ,onDelete="CASCADE")
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
     * Set codigo
     *
     * @param \AppBundle\Entity\Codigo $codigo
     *
     * @return FuentesCodigos
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
     * @return FuentesCodigos
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
