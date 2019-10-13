<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

     /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

     /**
     * @var string
     *
     */
    private $plainPassword;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255 , nullable=true)
     */
    private $phone;


    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles;

        /**
     * @var string
     *
     * @ORM\Column(name="rola", type="string", length=255  )
     */
    private $rola;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreSede", type="string", length=255)
     */
    private $nombreSede;


    /**
     * @var string
     *
     * @ORM\Column(name="ciudadSede", type="string", length=255)
     */
    private $ciudadSede;


    /**
     * @var string
     *
     * @ORM\Column(name="paisSede", type="string", length=255)
     */
    private $paisSede;


    /**
    * @ORM\ManyToOne(targetEntity="Caja")
    * @ORM\JoinColumn(name="caja", referencedColumnName="id" , nullable=true)
    */
    private $caja;

    /**
     * @var string
     *
     * @ORM\Column(name="pw", type="string", length=255 , nullable=true)
     */
    private $pw;

     

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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Users
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

    public function getPlainPassword()
    {
    return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return user
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Users
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Users
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Set image
     *
     * @param string $image
     *
     * @return Users
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
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = [$roles];
        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

     public function getSalt(){
        return null;
    }
    public function eraseCredentials(){
        
    }
public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->active;
    }


    /**
     * Set rola
     *
     * @param string $rola
     *
     * @return Users
     */
    public function setRola($rola)
    {
        $this->rola = $rola;

        return $this;
    }

    /**
     * Get rola
     *
     * @return string
     */
    public function getRola()
    {
        return $this->rola;
    }

    /**
     * Set caja
     *
     * @param \AppBundle\Entity\Caja $caja
     *
     * @return User
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set nombreSede
     *
     * @param string $nombreSede
     *
     * @return User
     */
    public function setNombreSede($nombreSede)
    {
        $this->nombreSede = $nombreSede;

        return $this;
    }

    /**
     * Get nombreSede
     *
     * @return string
     */
    public function getNombreSede()
    {
        return $this->nombreSede;
    }

    /**
     * Set ciudadSede
     *
     * @param string $ciudadSede
     *
     * @return User
     */
    public function setCiudadSede($ciudadSede)
    {
        $this->ciudadSede = $ciudadSede;

        return $this;
    }

    /**
     * Get ciudadSede
     *
     * @return string
     */
    public function getCiudadSede()
    {
        return $this->ciudadSede;
    }

    /**
     * Set paisSede
     *
     * @param string $paisSede
     *
     * @return User
     */
    public function setPaisSede($paisSede)
    {
        $this->paisSede = $paisSede;

        return $this;
    }

    /**
     * Get paisSede
     *
     * @return string
     */
    public function getPaisSede()
    {
        return $this->paisSede;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pw
     *
     * @param string $pw
     *
     * @return User
     */
    public function setPw($pw)
    {
        $this->pw = $pw;

        return $this;
    }

    /**
     * Get pw
     *
     * @return string
     */
    public function getPw()
    {
        return $this->pw;
    }
}
