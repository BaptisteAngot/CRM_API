<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RendezVousRepository::class)
 */
class RendezVous
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_end;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rendezVouses")
     */
    private $userIdHost;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="rendezVouses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $clientId;

    /**
     * @ORM\ManyToOne(targetEntity=Prospect::class, inversedBy="rendezVouses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $prospectId;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $invitedMail = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserIdHost(): ?User
    {
        return $this->userIdHost;
    }

    public function setUserIdHost(?User $userIdHost): self
    {
        $this->userIdHost = $userIdHost;

        return $this;
    }

    public function getClientId(): ?Client
    {
        return $this->clientId;
    }

    public function setClientId(?Client $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getProspectId(): ?Prospect
    {
        return $this->prospectId;
    }

    public function setProspectId(?Prospect $prospectId): self
    {
        $this->prospectId = $prospectId;

        return $this;
    }

    public function getInvitedMail(): ?array
    {
        return $this->invitedMail;
    }

    public function setInvitedMail(?array $invitedMail): self
    {
        $this->invitedMail = $invitedMail;

        return $this;
    }
}
