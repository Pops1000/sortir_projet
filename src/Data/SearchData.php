<?php
namespace App\Data;

use App\Entity\Campus;
use DateTime;

class SearchData {
    public ?string $q = null;
    public  ?Campus $campus = null;
    public ?DateTime $dateDebut = null ;
    public ?DateTime $dateFin = null;
    public bool $isOrganisateur = false;
    public bool $isInscrit = false;
    public bool $isNotInscrit = false;
    public bool $isPassees = false;

}