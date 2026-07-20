<?php

namespace App\Models;

use CodeIgniter\Model;

class gain extends Model
{
    protected $table = 'gain';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'idOperateur',
        'idHistorique',
        'valeur'
    ];

    protected $useTimestamps = false;
}