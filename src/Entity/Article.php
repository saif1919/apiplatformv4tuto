<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
//Les options dans ApiResource sauf les methodes HTTP (GET,POST,PUT,PATCH,DELETE)
//sont appliquées sur toutes les methodes
// #[ApiResource(
//     paginationEnabled: false,
//     denormalizationContext: ['groups' => ['write']],
//     normalizationContext: ['groups' => ['read']]
// )]

// #[GetCollection(
//     paginationEnabled: false
//     paginationItemsPerPage: 20,
//     paginationClientEnabled: true,
//     paginationClientItemsPerPage: true,
//     uriTemplate: '/getarticle',
//     name: 'getarticle'
// )]
// #[GetCollection(
//     uriTemplate: '/getarticle1',
//     name: 'getarticle1'
// )]

// #[GetCollection(
//     uriTemplate: '/getarticle2',
//     name: 'getarticle2',
//     filters: ['article.serach_filter']
// )]

#[GetCollection(
    normalizationContext: ['groups' => ['read']]
)]
#[Post(
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']]
)]
#[Put()]
#[Patch()]
#[Delete()]
#[Get()]
// #[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'content' => 'exact'])]

class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'not blank'
    )]
    #[Assert\Length(
        min: 3,
        max: 5,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['read','write'])]
    // #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    // #[ApiFilter(OrderFilter::class)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(
        message: 'not blank'
    )]
    #[Groups(['read', 'write'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups([ 'write'])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'article')]
    #[Groups(['read'])]
    #[ApiFilter(SearchFilter::class, properties:['author.firstName' => 'partial'])]
    private ?Author $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }
}
