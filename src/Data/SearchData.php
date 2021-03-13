<?php


namespace App\Data;


use DateTime;

class SearchData
{
    /**
     * @var string
     */
    public $mot_cle = '';

    /**
     * @var null|dateTime
     */
    public $date_min = '';

    /**
     * @var null|dateTime
     */
    public $date_max = '';

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



}