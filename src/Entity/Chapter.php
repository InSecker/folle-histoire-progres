<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 * @ApiResource(
 *  collectionOperations={
 *    "get"={
 *        "normalization_context"={
 *            "groups"={"chapters"}}
 *     }
 *  },
 *  itemOperations={"get"={"normalization_context"={"groups"={"chapter"}}}},
 *  normalizationContext={"groups"={"chapter", "chapters"}}
 * )
 */
class Chapter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"chapter", "chapters"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^[a-z0-9\-]+$/")
     * @Groups({"chapter", "chapters"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Groups({"chapter"})
     */
    private $intro;

    /**
     * @ORM\Column(type="text")
     * @Groups({"chapter"})
     */
    private $outro;

    /**
     * @ORM\OneToMany(targetEntity=Part::class, mappedBy="chapter")
     * @Groups({"chapter"})
     */
    private $parts;

    public function __construct()
    {
        $this->parts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function getOutro(): ?string
    {
        return $this->outro;
    }

    public function setOutro(string $outro): self
    {
        $this->outro = $outro;

        return $this;
    }

    /**
     * @return Collection|Part[]
     */
    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addPart(Part $part): self
    {
        if (!$this->parts->contains($part)) {
            $this->parts[] = $part;
            $part->setChapter($this);
        }

        return $this;
    }

    public function removePart(Part $part): self
    {
        if ($this->parts->contains($part)) {
            $this->parts->removeElement($part);
            // set the owning side to null (unless already changed)
            if ($part->getChapter() === $this) {
                $part->setChapter(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
