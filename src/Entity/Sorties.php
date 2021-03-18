<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\SortiesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortiesRepository::class)
 */
class Sorties
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private string $nom;

    /**
     * @ORM\Column(name="datedebut", type="datetime", nullable=false)
     */
    private \DateTime $datedebut;

    /**
     * @ORM\Column(name="duree", type="integer", nullable=true)
     */
    private ?int $duree;

    /**
     * @ORM\Column(name="datecloture", type="datetime", nullable=false)
     */
    private \DateTime $datecloture;

    /**
     * @ORM\Column(name="nbinscriptionsmax", type="integer", nullable=false)
     */
    private int $nbinscriptionsmax;

    /**
     * @ORM\Column(name="descriptioninfos", type="string", length=500, nullable=true)
     */
    private ?string $descriptioninfos;

    /**
     * @ORM\Column(name="etatsortie", type="integer", nullable=true)
     */
    private ?int $etatsortie;

    /**
     * @ORM\Column(name="urlPhoto", type="string", length=250, nullable=true)
     */
    private ?string $urlphoto;

    /**
     * @ORM\ManyToOne(targetEntity="Etats")
     */
    private Etats $etatsNoEtat;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Lieux")
     */
    private Lieux $lieuxNoLieu;

    /**
     * @ORM\ManyToOne(targetEntity="Participants")
     */
    private Participants $organisateur;

    /**
     * @ORM\OneToMany(targetEntity="Inscriptions", mappedBy="sorties_no_sortie_id")
     */
    private $inscriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatecloture(): ?\DateTimeInterface
    {
        return $this->datecloture;
    }

    public function setDatecloture(\DateTimeInterface $datecloture): self
    {
        $this->datecloture = $datecloture;

        return $this;
    }

    public function getNbinscriptionsmax(): ?int
    {
        return $this->nbinscriptionsmax;
    }

    public function setNbinscriptionsmax(int $nbinscriptionsmax): self
    {
        $this->nbinscriptionsmax = $nbinscriptionsmax;

        return $this;
    }

    public function getDescriptioninfos(): ?string
    {
        return $this->descriptioninfos;
    }

    public function setDescriptioninfos(?string $descriptioninfos): self
    {
        $this->descriptioninfos = $descriptioninfos;

        return $this;
    }

    public function getEtatsortie(): ?int
    {
        return $this->etatsortie;
    }

    public function setEtatsortie(?int $etatsortie): self
    {
        $this->etatsortie = $etatsortie;

        return $this;
    }

    public function getUrlphoto(): ?string
    {
        return $this->urlphoto;
    }

    public function setUrlphoto(?string $urlphoto): self
    {
        $this->urlphoto = $urlphoto;

        return $this;
    }

    public function getEtatsNoEtat(): ?Etats
    {
        return $this->etatsNoEtat;
    }

    public function setEtatsNoEtat(?Etats $etatsNoEtat): self
    {
        $this->etatsNoEtat = $etatsNoEtat;

        return $this;
    }

    public function getLieuxNoLieu(): ?Lieux
    {
        return $this->lieuxNoLieu;
    }

    public function setLieuxNoLieu(?Lieux $lieuxNoLieu): self
    {
        $this->lieuxNoLieu = $lieuxNoLieu;

        return $this;
    }

    public function getOrganisateur(): ?Participants
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participants $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection|Participants[]
     */
    public function getParticipantsNoParticipant(): Collection
    {
        return $this->inscriptions;
    }

    public function addParticipantsNoParticipant(Participants $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
        }

        return $this;
    }

    public function removeParticipantsNoParticipant(Participants $inscription): self
    {
        $this->inscriptions->removeElement($inscription);

        return $this;
    }

}
