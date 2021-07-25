<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="product", uniqueConstraints={@UniqueConstraint(name="unique_name", columns={"name"})})
 */
#[UniqueEntity('name', message: 'Product name must be unique')]
//TODO: Improve the other error messages
//TODO: Put all the autogenerated annotations into attributes
class Product implements \JsonSerializable
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=1, max=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=1, max=255)
     */
    private string $title;

    /**
     * Cents are because it's safer to make calculations in int rather in float.
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0)
     */
    private int $priceCents;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPriceCents(): int
    {
        return $this->priceCents;
    }

    public function getPriceDollars(): float
    {
        return $this->priceCents / 100;
    }

    public function setPriceCents(int $priceCents): void
    {
        $this->priceCents = $priceCents;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'priceDollars' => $this->getPriceDollars(),
        ];
    }
}
