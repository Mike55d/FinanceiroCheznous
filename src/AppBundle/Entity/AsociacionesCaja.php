<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AsociacionesCaja
 *
 * @ORM\Table(name="asociaciones_caja")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AsociacionesCajaRepository")
 */
class AsociacionesCaja
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
    * @ORM\JoinColumn(name="caja", referencedColumnName="id" ,onDelete="CASCADE")
    */
    private $caja;


    /**
    * @ORM\ManyToOne(targetEntity="Asociacion")
    * @ORM\JoinColumn(name="asociacion", referencedColumnName="id" ,onDelete="CASCADE")
    */
    private $asociacion;

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
     * Set caja
     *
     * @param \AppBundle\Entity\Caja $caja
     *
     * @return AsociacionesCaja
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
     * Set asociacion
     *
     * @param \AppBundle\Entity\Asociacion $asociacion
     *
     * @return AsociacionesCaja
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

    public function __toString(){

        return (string)$this->getAsociacion()->getId();
    }
}
