<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $date_inscription;

    /**
     * @ORM\ManyToOne(targetEntity="Sortie", inversedBy="inscription")
     */
    private Sortie $sortie;

    /**
     * @ORM\ManyToOne(targetEntity="Participant", inversedBy="inscription")
     */
    private Participant $participant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTime
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTime $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getSortie(): ?Sortie
    {
        return $this->sortie;
    }

    public function setSortie(Sortie $sortie): self
    {
        $this->sortie = $sortie;

        return $this;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}
