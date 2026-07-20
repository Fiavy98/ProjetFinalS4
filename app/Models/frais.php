<?php

namespace App\Models;

use CodeIgniter\Model;

class frais extends Model
{
    protected $table = 'frais';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'idTypeOperation',
        'min',
        'max',
        'valeur'
    ];

    protected $useTimestamps = false;
}