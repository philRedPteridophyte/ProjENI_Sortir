<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\SortieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
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
     * @ORM\Column(name="urlPhoto", type="string", length=250, nullable=true)
     */
    private ?string $urlphoto;

    /**
     * @ORM\ManyToOne(targetEntity="Etat")
     */
    private int $etat;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Lieu")
     */
    private Lieu $lieu;

    /**
     * @ORM\ManyToOne(targetEntity="Participant")
     */
    private Participant $organisateur;

    /**
     * @ORM\OneToMany(targetEntity="Inscription", mappedBy="sortie")
     */
    private $inscription;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inscription = new ArrayCollection();
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

    public function getUrlphoto(): ?string
    {
        return $this->urlphoto;
    }

    public function setUrlphoto(?string $urlphoto): self
    {
        $this->urlphoto = $urlphoto;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipant(): Collection
    {
        return $this->inscription;
    }

    public function addParticipant(Participant $inscription): self
    {
        if (!$this->inscription->contains($inscription)) {
            $this->inscription[] = $inscription;
        }

        return $this;
    }

    public function removeParticipant(Participant $inscription): self
    {
        $this->inscription->removeElement($inscription);

        return $this;
    }

}
