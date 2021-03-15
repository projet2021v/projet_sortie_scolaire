<?php


namespace App\Data;


use App\Entity\Site;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchData
{
    /**
     * @var string
     */
    public $mot_cle = '';

    /**
     * @var null|dateType
     */
    public $date_min;

    /**
     * @var null|dateType
     */
    public $date_max;

    /**
     * @var boolean
     */
    public $orga = false;

    /**
     * @var boolean
     */
    public $inscrit = false;

    /**
     * @var boolean
     */
    public $non_inscrit = false;

    /**
     * @var boolean
     */
    public $passee = false;

    /**
     * @var Site[]
     */
    public $site_sortie;



}