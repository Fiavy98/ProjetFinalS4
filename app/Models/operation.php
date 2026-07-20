<?php

namespace App\Models;

use CodeIgniter\Model;

class operation extends Model
{
    protected $table = 'operation';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'idTypeOperation',
        'idClient',
        'valeur',
        'idFrais',
        'description',
        'dateheure'
    ];

    protected $useTimestamps = false;
}