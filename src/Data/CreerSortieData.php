<?php


namespace App\Data;


use App\Entity\Lieu;
use App\Entity\Ville;
use DateTimeInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CreerSortieData
{
    /**
     * @var string
     */
    public $nom = '';

    /**
     * @var dateTimeInterface
     */
    public $date_heure_debut;

    /**
     * @var integer
     */
    public $duree;

    /**
     * @var dateTimeInterface
     */
    public $date_limite_inscription;

    /**
     * @var integer
     */
    public $nb_inscriptions_max;

    /**
     * @var string
     */
    public $infos_sortie;

    /**
     * @var Lieu
     */
    public $lieu;

    /**
     * @var Ville
     */
    public $ville;


    public function __toString() : string
    {
        return $this->nom;
    }


}