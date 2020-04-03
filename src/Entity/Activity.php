<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
{

    const TYPE_OF_MISC_FOOD_SHOP = 1;
    const TYPE_OF_HOME_DELIVERY = 2;
    const TYPE_OF_PHARMACY = 3;
    const TYPE_OF_BOOKSTORE = 4;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="activities")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMerchant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isShipping;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOrderable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingTimeMon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingTimeTue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingTimeWed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingTimeThu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingTimeFri;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingTimeSat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openingTimeSun;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPaymentCash;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPaymentCheck;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPaymentBankcard;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getIsMerchant(): ?bool
    {
        return $this->isMerchant;
    }

    public function setIsMerchant(?bool $isMerchant): self
    {
        $this->isMerchant = $isMerchant;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getIsShipping(): ?bool
    {
        return $this->isShipping;
    }

    public function setIsShipping(?bool $isShipping): self
    {
        $this->isShipping = $isShipping;

        return $this;
    }

    public function getIsOrderable(): ?bool
    {
        return $this->isOrderable;
    }

    public function setIsOrderable(?bool $isOrderable): self
    {
        $this->isOrderable = $isOrderable;

        return $this;
    }

    public function getOpeningTimeMon(): ?string
    {
        return $this->openingTimeMon;
    }

    public function setOpeningTimeMon(?string $openingTimeMon): self
    {
        $this->openingTimeMon = $openingTimeMon;

        return $this;
    }

    public function getOpeningTimeTue(): ?string
    {
        return $this->openingTimeTue;
    }

    public function setOpeningTimeTue(?string $openingTimeTue): self
    {
        $this->openingTimeTue = $openingTimeTue;

        return $this;
    }

    public function getOpeningTimeWed(): ?string
    {
        return $this->openingTimeWed;
    }

    public function setOpeningTimeWed(?string $openingTimeWed): self
    {
        $this->openingTimeWed = $openingTimeWed;

        return $this;
    }

    public function getOpeningTimeThu(): ?string
    {
        return $this->openingTimeThu;
    }

    public function setOpeningTimeThu(?string $openingTimeThu): self
    {
        $this->openingTimeThu = $openingTimeThu;

        return $this;
    }

    public function getOpeningTimeFri(): ?string
    {
        return $this->openingTimeFri;
    }

    public function setOpeningTimeFri(?string $openingTimeFri): self
    {
        $this->openingTimeFri = $openingTimeFri;

        return $this;
    }

    public function getOpeningTimeSat(): ?string
    {
        return $this->openingTimeSat;
    }

    public function setOpeningTimeSat(?string $openingTimeSat): self
    {
        $this->openingTimeSat = $openingTimeSat;

        return $this;
    }

    public function getOpeningTimeSun(): ?string
    {
        return $this->openingTimeSun;
    }

    public function setOpeningTimeSun(?string $openingTimeSun): self
    {
        $this->openingTimeSun = $openingTimeSun;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isTypeMiscFoodShop(): bool
    {
        return self::TYPE_OF_MISC_FOOD_SHOP === $this->type;
    }

    public function isHomeDelivery(): bool
    {
        return self::TYPE_OF_HOME_DELIVERY === $this->type;
    }

    public function isPharmacy(): bool
    {
        return self::TYPE_OF_PHARMACY === $this->type;
    }
    
    public function isBookstore(): bool
    {
        return self::TYPE_OF_BOOKSTORE === $this->type;
    }


    public function getLongTypeOf(): ?string
    {
        if (array_key_exists($this->type, self::getTypeOfs())) {
            return self::getTypeOfs()[$this->type];
        }

        return null;
    }

    public static function getTypeOfs(): array
    {
        return [
            self::TYPE_OF_MISC_FOOD_SHOP => 'COMMERCES ALIMENTAIRES DIVERS',
            self::TYPE_OF_HOME_DELIVERY  => 'LIVRAISON A DOMICILE',
            self::TYPE_OF_PHARMACY       => 'PHARMACIES',        
            self::TYPE_OF_BOOKSTORE      => 'LIBRAIRIES'        
        ];
    }

    public function isPaymentCash(): ?bool
    {
        return $this->isPaymentCash;
    }

    public function setIsPaymentCash(?bool $isPaymentCash): self
    {
        $this->isPaymentCash = $isPaymentCash;

        return $this;
    }

    public function isPaymentCheck(): ?bool
    {
        return $this->isPaymentCheck;
    }

    public function setIsPaymentCheck(?bool $isPaymentCheck): self
    {
        $this->isPaymentCheck = $isPaymentCheck;

        return $this;
    }

    public function isPaymentBankcard(): ?bool
    {
        return $this->isPaymentBankcard;
    }

    public function setIsPaymentBankcard(?bool $isPaymentBankcard): self
    {
        $this->isPaymentBankcard = $isPaymentBankcard;

        return $this;
    }

}
