<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ParticipantsRepository::class)
 * @UniqueEntity(fields={"mail"}, message="Il y a déjà un compte avec cet e-mail")
 * @UniqueEntity(fields={"pseudo"}, message="Il y a déjà un compte avec ce pseudo")
 */
class Participants
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     *
     * @ORM\Column(name="pseudo", type="string", length=30, nullable=false)
     */
    private string $pseudo;

    /**
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private string $nom;

    /**
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    private string $prenom;

    /**
     *
     * @ORM\Column(name="telephone", type="string", length=15, nullable=true)
     */
    private string $telephone;

    /**
     *
     * @ORM\Column(name="mail", type="string", length=225, nullable=false)
     */
    private string $mail;

    /**
     *
     * @ORM\Column(name="mot_de_passe", type="string", length=20, nullable=false)
     */
    private string $motDePasse;

    /**
     *
     * @ORM\Column(name="administrateur", type="boolean", nullable=false)
     */
    private bool $administrateur;

    /**
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private bool $actif;

    /**
     * @ORM\Column(name="sites_no_site", type="integer", nullable=false)
     */
    private int $sites_id;

    /**
     * @ORM\OneToMany(targetEntity="Inscriptions", mappedBy="participants_no_participant")
     */
    private \Doctrine\Common\Collections\Collection $inscriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inscriptions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSitesId(): ?int
    {
        return $this->sites_id;
    }

    public function setSitesId(int $sites_id): self
    {
        $this->sites_id = $sites_id;

        return $this;
    }

    /**
     * @return Collection|Inscriptions[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscriptions(Inscriptions $inscriptions): self
    {
        if (!$this->inscriptions->contains($inscriptions)) {
            $this->inscriptions[] = $inscriptions;
            $inscriptions->addParticipantsNoParticipant($this);
        }

        return $this;
    }

    public function removeInscriptions(Inscriptions $inscriptions): self
    {
        if ($this->inscriptions->removeElement($inscriptions)) {
            $inscriptions->removeParticipantsNoParticipant($this);
        }

        return $this;
    }


}
