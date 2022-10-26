<?php

namespace App\Entity;

use App\Repository\FavoritesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritesRepository::class)]
#[ORM\Table(name: '`favorites`')]
class Favorites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $user_id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $word_id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $count = null;

    /**
     * @var datetime $createdAt
     */
    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private $updatedAt;

    /**
     * @var datetime $deletedAt
     */
    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private $deletedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getWordId(): ?string
    {
        return $this->word_id;
    }

    public function setWordId(string $word_id): self
    {
        $this->word_id = $word_id;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return datetime
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime('now');

        return $this;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    /**
     * @return datetime
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    public function setDeleteddAt(): self
    {
        $this->deletedAt = new \DateTime('now');

        return $this;
    }
}
