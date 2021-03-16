<?php

namespace App\Entity;

use App\Repository\LieuxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LieuxRepository::class)
 */
class Lieux
{
    /**
     * @ORM\Column(name="no_lieu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $noLieu;

    /**
     * @ORM\Column(name="nom_lieu", type="string", length=30, nullable=false)
     */
    private string $nomLieu;

    /**
     * @ORM\Column(name="rue", type="string", length=30, nullable=true)
     */
    private ?string $rue;

    /**
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private ?float $latitude;

    /**
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private ?float $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="Villes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="villes_no_ville", referencedColumnName="no_ville")
     * })
     */
    private ?Villes $villesNoVille;

    public function getNoLieu(): ?int
    {
        return $this->noLieu;
    }

    public function getNomLieu(): ?string
    {
        return $this->nomLieu;
    }

    public function setNomLieu(string $nomLieu): self
    {
        $this->nomLieu = $nomLieu;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVillesNoVille(): ?Villes
    {
        return $this->villesNoVille;
    }

    public function setVillesNoVille(?Villes $villesNoVille): self
    {
        $this->villesNoVille = $villesNoVille;

        return $this;
    }


}
