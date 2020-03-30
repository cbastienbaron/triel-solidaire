<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CollectRepository")
 */
class Collect
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referent", inversedBy="collects")
     */
    private $assignedTo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\District", inversedBy="collects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $district;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCollected = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infos;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $internalDescription;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

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

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getIsCollected(): ?bool
    {
        return $this->isCollected;
    }

    public function setIsCollected(bool $isCollected): self
    {
        $this->isCollected = $isCollected;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(?string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    public function getInternalDescription(): ?string
    {
        return $this->internalDescription;
    }

    public function setInternalDescription(?string $internalDescription): self
    {
        $this->internalDescription = $internalDescription;

        return $this;
    }
}
