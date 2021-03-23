<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"mail"}, message="Il y a déjà un compte avec cet e-mail")
 * @UniqueEntity(fields={"pseudo"}, message="Il y a déjà un compte avec ce pseudo")
 */
class Participant
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
     * @ORM\ManyToOne(targetEntity="Site")
     */
    private Site $site;

    /**
     * @ORM\OneToMany(targetEntity="Inscription", mappedBy="participant")
     */
    private \Doctrine\Common\Collections\Collection $inscription;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inscription = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscription(): Collection
    {
        return $this->inscription;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscription->contains($inscription)) {
            $this->inscription[] = $inscription;
            $inscription->addParticipant($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscription->removeElement($inscription)) {
            $inscription->removeParticipant($this);
        }

        return $this;
    }


}
