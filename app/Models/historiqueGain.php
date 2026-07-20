<?php

namespace App\Models;

use CodeIgniter\Model;

class historiqueGain extends Model
{
    protected $table = 'historiqueGain';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'dateheure',
        'idOperation',
        'valeur'
    ];

    protected $useTimestamps = false;
}