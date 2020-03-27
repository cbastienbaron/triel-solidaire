<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DonationRepository")
 */
class Donation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="vous devez renseigner votre identité")
     * @ORM\Column(type="string", length=255)
     */
    private $person;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quartier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalInfo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $donateAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCollected = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TypeOfDonation", inversedBy="donations")
     */
    private $typeOfDonations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipient", inversedBy="donations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referent", inversedBy="donations")
     */
    private $assignedTo;


    public function __construct()
    {
        $this->typeOfDonations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(string $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?string $additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    public function getDonateAt(): ?\DateTimeInterface
    {
        return $this->donateAt;
    }

    public function setDonateAt(?\DateTimeInterface $donateAt): self
    {
        $this->donateAt = $donateAt;

        return $this;
    }

    /**
     * @return Collection|TypeOfDonation[]
     */
    public function getTypeOfDonations(): Collection
    {
        return $this->typeOfDonations;
    }
    
    public function setTypeOfDonations(Collection $typeOfDonations): self 
    {
        $this->typeOfDonations = $typeOfDonations;
        return $this;
    }

    public function addTypeOfDonation(TypeOfDonation $typeOfDonation): self
    {
        if (!$this->typeOfDonations->contains($typeOfDonation)) {
            $this->typeOfDonations[] = $typeOfDonation;
        }

        return $this;
    }

    public function removeTypeOfDonation(TypeOfDonation $typeOfDonation): self
    {
        if ($this->typeOfDonations->contains($typeOfDonation)) {
            $this->typeOfDonations->removeElement($typeOfDonation);
        }

        return $this;
    }

    public function getRecipient(): ?Recipient
    {
        return $this->recipient;
    }

    public function setRecipient(?Recipient $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get the value of isCollected
     */ 
    public function isCollected(): bool
    {
        return $this->isCollected;
    }

    /**
     * Set the value of isCollected
     *
     * @return  self
     */ 
    public function setIsCollected($isCollected)
    {
        $this->isCollected = $isCollected;

        return $this;
    }

    /**
     * Get the value of quartier
     */ 
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * Set the value of quartier
     *
     * @return  self
     */ 
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;

        return $this;
    }

    public function getAssignedTo(): ?Referent
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?Referent $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function __toString()
    {
        return $this->person;
    }
}
