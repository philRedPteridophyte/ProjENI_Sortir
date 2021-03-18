<?php

namespace App\Entity;

use App\Repository\InscriptionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionsRepository::class)
 */
class Inscriptions
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
     * @ORM\ManyToOne(targetEntity="Sorties", inversedBy="inscriptions")
     * @ORM\Column(type="integer")
     */
    private int  $sorties_no_sortie_id;

    /**
     * @ORM\ManyToOne(targetEntity="Participants", inversedBy="inscriptions" )
     * @ORM\Column(type="integer")
     */
    private int $participants_no_participant_id;

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

    public function getSortiesNoSortieId(): ?int
    {
        return $this->sorties_no_sortie_id;
    }

    public function setSortiesNoSortieId(int $sorties_no_sortie_id): self
    {
        $this->sorties_no_sortie_id = $sorties_no_sortie_id;

        return $this;
    }

    public function getParticipantsNoParticipantId(): ?int
    {
        return $this->participants_no_participant_id;
    }

    public function setParticipantsNoParticipantId(int $participants_no_participant_id): self
    {
        $this->participants_no_participant_id = $participants_no_participant_id;

        return $this;
    }
}
