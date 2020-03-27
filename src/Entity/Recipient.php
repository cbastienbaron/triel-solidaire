<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipientRepository")
 */
class Recipient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TypeOfDonation", mappedBy="recipient")
     */
    private $typeOfDonations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Donation", mappedBy="recipient")
     */
    private $donations;

    public function __construct()
    {
        $this->typeOfDonations = new ArrayCollection();
        $this->donations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|TypeOfDonation[]
     */
    public function getTypeOfDonations(): Collection
    {
        return $this->typeOfDonations;
    }

    public function addTypeOfDonation(TypeOfDonation $typeOfDonation): self
    {
        if (!$this->typeOfDonations->contains($typeOfDonation)) {
            $this->typeOfDonations[] = $typeOfDonation;
            $typeOfDonation->setRecipient($this);
        }

        return $this;
    }

    public function removeTypeOfDonation(TypeOfDonation $typeOfDonation): self
    {
        if ($this->typeOfDonations->contains($typeOfDonation)) {
            $this->typeOfDonations->removeElement($typeOfDonation);
            // set the owning side to null (unless already changed)
            if ($typeOfDonation->getRecipient() === $this) {
                $typeOfDonation->setRecipient(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Donation[]
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonation(Donation $donation): self
    {
        if (!$this->donations->contains($donation)) {
            $this->donations[] = $donation;
            $donation->setRecipient($this);
        }

        return $this;
    }

    public function removeDonation(Donation $donation): self
    {
        if ($this->donations->contains($donation)) {
            $this->donations->removeElement($donation);
            // set the owning side to null (unless already changed)
            if ($donation->getRecipient() === $this) {
                $donation->setRecipient(null);
            }
        }

        return $this;
    }
}
