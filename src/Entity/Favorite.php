<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
#[ORM\Table(name: '`user_favorites`')]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $user_id = null;

    #[ORM\Column(type: 'integer', length: 255, unique: true)]
    private ?int $word_id = null;

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

    public function setId(int $id):self
    {
        $this->id = $id;

        return $this;
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
