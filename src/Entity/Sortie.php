<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_heure_debut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="date")
     */
    private $date_limite_inscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_inscriptions_max;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infos_sortie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $motif_annulation;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="sortie")
     */
    private $inscriptions;

//    /**
//     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="sorties")
//     */
//    private $participants;

    public function __construct()
    {
//        $this->participants = new ArrayCollection();
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->date_heure_debut;
    }

    public function setDateHeureDebut(\DateTimeInterface $date_heure_debut): self
    {
        $this->date_heure_debut = $date_heure_debut;

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

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->date_limite_inscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $date_limite_inscription): self
    {
        $this->date_limite_inscription = $date_limite_inscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nb_inscriptions_max;
    }

    public function setNbInscriptionsMax(int $nb_inscriptions_max): self
    {
        $this->nb_inscriptions_max = $nb_inscriptions_max;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infos_sortie;
    }

    public function setInfosSortie(?string $infos_sortie): self
    {
        $this->infos_sortie = $infos_sortie;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motif_annulation;
    }

    public function setMotifAnnulation(?string $motif_annulation): self
    {
        $this->motif_annulation = $motif_annulation;

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

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

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

//    /**
//     * @return Collection|Participant[]
//     */
//    public function getParticipants(): Collection
//    {
//        return $this->participants;
//    }
//
//    public function addParticipant(Participant $participant): self
//    {
//        if (!$this->participants->contains($participant)) {
//            $this->participants[] = $participant;
//        }
//
//        return $this;
//    }
//
//    public function removeParticipant(Participant $participant): self
//    {
//        $this->participants->removeElement($participant);
//
//        return $this;
//    }

/**
 * @return Collection|Inscription[]
 */
public function getInscriptions(): Collection
{
    return $this->inscriptions;
}

public function addInscription(Inscription $inscription): self
{
    if (!$this->inscriptions->contains($inscription)) {
        $this->inscriptions[] = $inscription;
        $inscription->setSortie($this);
    }

    return $this;
}

public function removeInscription(Inscription $inscription): self
{
    if ($this->inscriptions->removeElement($inscription)) {
        // set the owning side to null (unless already changed)
        if ($inscription->getSortie() === $this) {
            $inscription->setSortie(null);
        }
    }

    return $this;
}
}
