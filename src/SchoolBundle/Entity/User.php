<?php

namespace SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="SchoolBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z_]{3,20}$/",         
     *     message="Le nom d'utilisateur doit faire au moins 3 caractères et ne peut pas en éxéder 20."
     * )
     */
    private $username;


    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="activity", type="datetime", nullable=true)
     */
    private $activity;


    /**
    * @ORM\Column(name="role", type="string", length=255)
    */
    private $role;

    public function __construct(){
        $this->activity = new \DateTime();
    }


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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Teacher
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Teacher
     */
    public function setPassword($password)
    {
        if(empty($password))
            $password=null;

        $this->password = sha1($password) ;

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




    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        return $this->username = $username;
    }


    /**
     * Set activity
     *
     * @param \DateTime $activity
     *
     * @return User
     */
    public function setActivity($date)
    {
        $this->activity = $date;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \DateTime
     */
    public function getActivity()
    {
        return $this->activity;
    }


    public function getRole(){
        if(empty($this->role)){
            return 'ROLE_USER';
        }
        else{
            return $this->role;
        }
    }

    public function getRoles(){
        return array($this->getRole());
    }

    public function getRoleName(){
        $rolesName = User::getListRolesName();
        return $rolesName[$this->role];
    }

    public function setRole($role){
        $this->role = $role;
    }

    public function getFullName(){
        return ucfirst($this->firstname)." ".strtoupper($this->lastname);
    }

    public function getLogin(){
        if(!empty($this->username)){
            return $this->username;
        }
        else{
            return strtolower($this->firstname.'.'.$this->lastname);
        }
    }


    // For encoding password
    public function getSalt(){
        return null;
    }

    public function eraseCredentials(){

    }

    public static function getListRolesName(){
        return array(
            "ROLE_TEACHER" => "Enseignant",
            "" => "Elève",              
        );
    }
}

