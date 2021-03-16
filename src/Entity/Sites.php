<?php

namespace App\Entity;

use App\Repository\SitesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SitesRepository::class)
 */
class Sites
{
    /**
     * @ORM\Column(name="no_site", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $noSite;

    /**
     * @ORM\Column(name="nom_site", type="string", length=30, nullable=false)
     */
    private string $nomSite;

    public function getNoSite(): ?int
    {
        return $this->noSite;
    }

    public function getNomSite(): ?string
    {
        return $this->nomSite;
    }

    public function setNomSite(string $nomSite): self
    {
        $this->nomSite = $nomSite;

        return $this;
    }


}
