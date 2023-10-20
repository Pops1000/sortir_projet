<?php
namespace App\Data;

use App\Entity\Campus;

class SearchData {
    public string $q = '';
    public array $campus = [];
    public DateTime $dateDebut;
    public DateTime $dateFin;
    public bool $isOrganisateur = false;
    public bool $isInscrit = false;
    public bool $isNotInscrit = false;
    public bool $isPassees = false;

}