<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\LieuxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LieuxRepository::class)
 */
class Participants
{
    /**
     * @var int
     *
     * @ORM\Column(name="no_participant", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noParticipant;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=30, nullable=false)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=20, nullable=false)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="mot_de_passe", type="string", length=20, nullable=false)
     */
    private $motDePasse;

    /**
     * @var bool
     *
     * @ORM\Column(name="administrateur", type="boolean", nullable=false)
     */
    private $administrateur;

    /**
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private bool $actif;

    /**
     * @ORM\Column(name="sites_no_site", type="integer", nullable=false)
     */
    private int $sitesNoSite;

    /**
     * @ORM\OneToMany(targetEntity="Inscriptions", mappedBy="participantsNoParticipant")
     */
    private Collection $inscriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sortiesNoSortie = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getNoParticipant(): ?int
    {
        return $this->noParticipant;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getSitesNoSite(): ?int
    {
        return $this->sitesNoSite;
    }

    public function setSitesNoSite(int $sitesNoSite): self
    {
        $this->sitesNoSite = $sitesNoSite;

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getSortiesNoSortie(): Collection
    {
        return $this->sortiesNoSortie;
    }

    public function addSortiesNoSortie(Sorties $sortiesNoSortie): self
    {
        if (!$this->sortiesNoSortie->contains($sortiesNoSortie)) {
            $this->sortiesNoSortie[] = $sortiesNoSortie;
            $sortiesNoSortie->addParticipantsNoParticipant($this);
        }

        return $this;
    }

    public function removeSortiesNoSortie(Sorties $sortiesNoSortie): self
    {
        if ($this->sortiesNoSortie->removeElement($sortiesNoSortie)) {
            $sortiesNoSortie->removeParticipantsNoParticipant($this);
        }

        return $this;
    }

}
