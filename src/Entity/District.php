<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 */
class District
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
     * @ORM\OneToMany(targetEntity="App\Entity\Referent", mappedBy="district")
     */
    private $referents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Donation", mappedBy="district")
     */
    private $donations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collect", mappedBy="district")
     */
    private $collects;

    public function __construct()
    {
        $this->referents = new ArrayCollection();
        $this->donations = new ArrayCollection();
        $this->collects = new ArrayCollection();
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
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection|Referent[]
     */
    public function getReferents(): Collection
    {
        return $this->referents;
    }

    public function addReferent(Referent $referent): self
    {
        if (!$this->referents->contains($referent)) {
            $this->referents[] = $referent;
            $referent->setDistrict($this);
        }

        return $this;
    }

    public function removeReferent(Referent $referent): self
    {
        if ($this->referents->contains($referent)) {
            $this->referents->removeElement($referent);
            // set the owning side to null (unless already changed)
            if ($referent->getDistrict() === $this) {
                $referent->setDistrict(null);
            }
        }

        return $this;
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
            $donation->setDistrict($this);
        }

        return $this;
    }

    public function removeDonation(Donation $donation): self
    {
        if ($this->donations->contains($donation)) {
            $this->donations->removeElement($donation);
            // set the owning side to null (unless already changed)
            if ($donation->getDistrict() === $this) {
                $donation->setDistrict(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Collect[]
     */
    public function getCollects(): Collection
    {
        return $this->collects;
    }

    public function addCollect(Collect $collect): self
    {
        if (!$this->collects->contains($collect)) {
            $this->collects[] = $collect;
            $collect->setDistrict($this);
        }

        return $this;
    }

    public function removeCollect(Collect $collect): self
    {
        if ($this->collects->contains($collect)) {
            $this->collects->removeElement($collect);
            // set the owning side to null (unless already changed)
            if ($collect->getDistrict() === $this) {
                $collect->setDistrict(null);
            }
        }

        return $this;
    }
}
