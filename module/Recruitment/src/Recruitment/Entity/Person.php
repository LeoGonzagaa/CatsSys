<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Recruitment\Entity;

use Authentication\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Recruitment\Entity\Address;
use Recruitment\Entity\Person;

/**
 * Description of Person
 * @author Márcio
 * @ORM\Table(name="person", 
 *      indexes={@ORM\Index(name="person_firstname_idx", columns={"person_firstname"})}
 * )
 * @ORM\Entity
 */
class Person
{

    const DEFAULT_FEMALE_PHOTO = 'default-female-profile.png';
    const DEFAULT_MALE_PHOTO = 'default-male-profile.png';
    const MAJORITY = 18;

    /**
     * 1 - Feminino
     * 2 - Masculino
     */
    const GENDER_F = 1;
    const GENDER_M = 2;

    /**
     * @var integer
     * @ORM\Column(name="person_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $personId;

    /**
     * @var string
     * @ORM\Column(name="person_firstname", type="string", length=80, nullable=false)
     */
    private $personFirstName;

    /**
     * @var string
     * @ORM\Column(name="person_lastname", type="string" , length=200, nullable=false)
     */
    private $personLastName;

    /**
     *
     * @var integer
     * @ORM\Column(name="person_gender", type="smallint", nullable=false)
     */
    private $personGender;

    /**
     *
     * @var string
     * @ORM\Column(name="person_rg", type="string", length=25, nullable=false)
     */
    private $personRg;

    /**
     *
     * @var string
     * @ORM\Column(name="person_cpf", type="string", length=14, nullable=false, unique=true)
     */
    private $personCpf;

    /**
     * @var string
     * @ORM\Column(name="person_email", type="string", length=50, nullable=false)
     */
    private $personEmail;

    /**
     * @var string
     * @ORM\Column(name="person_socialmedia", type="string", length=200, nullable=true)
     */
    private $personSocialMedia;

    /**
     *
     * @var string
     * @ORM\Column(name="person_photo", type="string", length=200, nullable=true)
     */
    private $personPhoto;

    /**
     *
     * @var string
     * @ORM\Column(name="person_phone", type="string", length=20, nullable=true)
     */
    private $personPhone;

    /**
     * @var string
     * @ORM\Column(name="person_alternative_phone", type="string", length=20, nullable=true)
     */
    private $personAlternativePhone;

    /**
     *
     * @var DateTime
     * @ORM\Column(name="person_birthday", type="date", nullable=false)
     */
    private $personBirthday;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Address", inversedBy="people", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="person_has_address",
     *   joinColumns={
     *     @ORM\JoinColumn(name="person_id", referencedColumnName="person_id", nullable=false)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="address_id", referencedColumnName="address_id", nullable=false)
     *   }
     * )
     */
    private $addresses;

    /**
     *
     * @var User
     * @ORM\OneToOne(targetEntity="\Authentication\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $user;

    /**
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Registration", mappedBy="person", fetch="EXTRA_LAZY")
     */
    private $registrations;

    /**
     *
     * @var Collection 
     * @ORM\OneToMany(targetEntity="Relative", mappedBy="person", fetch="EXTRA_LAZY")
     */
    private $isRelativeOf;

    /**
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Relative", mappedBy="relative", fetch="EXTRA_LAZY")
     */
    private $relatives;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->relatives = new ArrayCollection();
        $this->isRelativeOf = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    /**
     * Get Person ID
     * @return integer
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param integer $personId
     * @return Person
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
        return $this;
    }

    /**
     * Get Person firstname
     * @return string
     */
    public function getPersonFirstName()
    {
        return $this->personFirstName;
    }

    /**
     * Get Person lastname
     * @return string
     */
    public function getPersonLastName()
    {
        return $this->personLastName;
    }

    /**
     * 
     * @return \Datetime
     */
    public function getPersonBirthday()
    {
        return $this->personBirthday;
    }

    /**
     * 
     * @param \Datetime $personBirthday
     */
    public function setPersonBirthday(\Datetime $personBirthday)
    {
        $this->personBirthday = $personBirthday;
        return $this;
    }

    /**
     * Get person RG
     * @return string
     */
    public function getPersonRg()
    {
        return $this->personRg;
    }

    /**
     * Get person CPF
     * @return string
     */
    public function getPersonCpf()
    {
        return $this->personCpf;
    }

    /**
     * Get Person Email
     * @return string
     */
    public function getPersonEmail()
    {
        return $this->personEmail;
    }

    /**
     * Get Person socialmedia link
     * @return string
     */
    public function getPersonSocialMedia()
    {
        return $this->personSocialMedia;
    }

    /**
     * Get person photo url
     * @return string
     */
    public function getPersonPhoto()
    {
        return $this->personPhoto;
    }

    /**
     * 
     * @param mixed $personPhoto string or null
     * @return Person
     */
    public function setPersonPhoto($personPhoto = null)
    {
        if ($personPhoto === null) {
            if ($this->personPhoto === null) {

                switch ($this->personGender) {
                    case self::GENDER_F:
                        $this->personPhoto = self::DEFAULT_FEMALE_PHOTO;
                        break;
                    case self::GENDER_M:
                        $this->personPhoto = self::DEFAULT_MALE_PHOTO;
                        break;
                }
            }
        } else {
            $this->personPhoto = $personPhoto;
        }

        return $this;
    }

    /**
     * Get Person Phone
     * @return string
     */
    public function getPersonPhone()
    {
        return $this->personPhone;
    }

    /**
     * Get Person Alternative Phone
     * @return string
     */
    public function getPersonAlternativePhone()
    {
        return $this->personAlternativePhone;
    }

    /**
     * Get Person Addresses
     * @return Collection
     */
    public function getAddresses()
    {
        return $this->addresses->toArray();
    }

    /**
     * Set Person Firstname
     * @param string $personFirstName
     */
    public function setPersonFirstName($personFirstName)
    {
        $this->personFirstName = $personFirstName;
        return $this;
    }

    /**
     * 
     * @param string $personLastName
     */
    public function setPersonLastName($personLastName)
    {
        $this->personLastName = $personLastName;
        return $this;
    }

    /**
     * 
     * @param string $personRg
     */
    public function setPersonRg($personRg)
    {
        $this->personRg = $personRg;
        return $this;
    }

    /**
     * 
     * @param string $personCpf
     */
    public function setPersonCpf($personCpf)
    {
        $this->personCpf = $personCpf;
        return $this;
    }

    /**
     * 
     * @param string $personEmail
     */
    public function setPersonEmail($personEmail)
    {
        $this->personEmail = $personEmail;
        return $this;
    }

    /**
     * 
     * @param string $personSocialMedia
     */
    public function setPersonSocialMedia($personSocialMedia)
    {
        $this->personSocialMedia = $personSocialMedia;
        return $this;
    }

    /**
     * 
     * @param string $personPhone
     */
    public function setPersonPhone($personPhone)
    {
        $this->personPhone = $personPhone;
        return $this;
    }

    /**
     * 
     * @param string $personAlternativePhone
     */
    public function setPersonAlternativePhone($personAlternativePhone)
    {
        $this->personAlternativePhone = $personAlternativePhone;
        return $this;
    }

    /**
     * @param Collection $addresses
     */
    public function setAddresses(Collection $addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * Add address
     *
     * @param Address $address
     *
     * @return Person
     */
    public function addAddress(Address $address)
    {
        $this->addresses->add($address);
        return $this;
    }

    /**
     * 
     * @param Address $address
     * @return boolean
     */
    public function hasAddress(Address $address)
    {
        return $this->addresses->contains($address);
    }

    /**
     * Remove address
     *
     * @param Address $address
     */
    public function removeAddress(Address $address)
    {
        $this->addresses->removeElement($address);
        return $this;
    }

    /**
     * 
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * 
     * @return array
     */
    public function getRegistrations()
    {
        return $this->registrations->toArray();
    }

    /**
     * 
     * @param User $user
     * @return Person
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * 
     * @param Collection $registrations
     * @return Person
     */
    public function setRegistrations(Collection $registrations)
    {
        $this->registrations = $registrations;
        return $this;
    }

    /**
     * @param \Recruitment\Entity\Registration $registration
     * @return Person
     */
    public function addRegistration(Registration $registration)
    {
        $this->registrations->add($registration);
        return $this;
    }

    /**
     * 
     * @param \Recruitment\Entity\Registration $registration
     * @return Person
     */
    public function removeRegistration(Registration $registration)
    {
        $this->registrations->removeElement($registration);
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getIsRelativeOf()
    {
        return $this->isRelativeOf->toArray();
    }

    /**
     * 
     * @param Relative $relative
     * @return Person
     */
    public function addIsRelativeOf(Relative $relative)
    {
        $this->isRelativeOf->add($relative);
        return $this;
    }

    /**
     * 
     * @param Relative $relative
     * @return Person
     */
    public function removeIsRelativeOf(Relative $relative)
    {
        $this->isRelativeOf->removeElement($relative);
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getRelatives()
    {
        return $this->relatives->toArray();
    }

    /**
     * 
     * @param Relative $relative
     * @return Person
     */
    public function addRelative(Relative $relative)
    {
        $this->relatives->add($relative);
        return $this;
    }

    /**
     * 
     * @param Relative $relative
     * @return Person
     */
    public function removeRelative(Relative $relative)
    {
        $this->relatives->removeElement($relative);
        return $this;
    }

    /**
     * 
     * @return integer;
     */
    public function getPersonGender()
    {
        return $this->personGender;
    }

    /**
     * 
     * @param integer $personGender
     * @return Person
     * @throws \InvalidArgumentException
     */
    public function setPersonGender($personGender)
    {
        if (in_array($personGender, array(
                self::GENDER_F,
                self::GENDER_M
            ))) {
            $this->personGender = $personGender;
            return $this;
        }

        throw new \InvalidArgumentException('invalid gender.');
    }

    /**
     * 
     * @return string
     */
    public function getPersonName()
    {
        return $this->personFirstName . ' ' . $this->personLastName;
    }

    public function getPersonAge()
    {
        $today = new \DateTime('now');
        return $today->diff($this->personBirthday)->y;
    }

    /**
     * 
     * @return boolean
     */
    public function isPersonUnderage()
    {
        return $this->getPersonAge() < self::MAJORITY;
    }

}
