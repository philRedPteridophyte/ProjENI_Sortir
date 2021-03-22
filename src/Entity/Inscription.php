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
    private int $sortie_id;

    /**
     * @ORM\ManyToOne(targetEntity="Participant", inversedBy="inscription")
     */
    private int $participant_id;

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

    public function getSortie(): ?int
    {
        return $this->sortie_id;
    }

    public function setSortie(int $sortie): self
    {
        $this->sortie_id = $sortie;

        return $this;
    }

    public function getParticipant(): ?int
    {
        return $this->participant_id;
    }

    public function setParticipant(int $participant): self
    {
        $this->participant_id = $participant;

        return $this;
    }
}
