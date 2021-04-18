<?php

namespace App\Entity;

use App\Repository\AdvisorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=AdvisorRepository::class)
 */
class Advisor implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $availability = false;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $pricePerMinute;

    /**
     * @ORM\Column(type="json")
     */
    private $languages = [];

    /**
     * @ORM\Column(type="binary", length=2097152, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @var
     */
    private $imageMime;

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

    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(?bool $availability): self
    {
        $this->availability = $availability;
        return $this;
    }

    public function getPricePerMinute(): ?string
    {
        return $this->pricePerMinute;
    }

    public function setPricePerMinute(string $pricePerMinute): self
    {
        $this->pricePerMinute = $pricePerMinute;
        return $this;
    }

    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    public function setLanguages(array $languages): self
    {
        $this->languages = $languages;
        return $this;
    }

    public function getImage()
    {
        if (!$this->image) {
            return null;
        }
        return stream_get_contents($this->image);
    }

    public function setImage(UploadedFile $image): self
    {
        $fullName = $image->getPath().DIRECTORY_SEPARATOR.$image->getFilename();
        if (!exif_imagetype($fullName)){
            throw new \Exception("Non-image file");
        }
        $data = $image->getContent();
        $this->image = $data;
        $this->imageMime = $image->getClientMimeType();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageMime()
    {
        return $this->imageMime;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'availability' => $this->getAvailability(),
            'pricePerMinute' => $this->getPricePerMinute(),
            'languages' => $this->getLanguages(),
        ];
    }
}
