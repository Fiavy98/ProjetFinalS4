<?php

namespace App\Models;

use CodeIgniter\Model;

class historiqueOperationClient extends Model
{
    protected $table = 'historiqueOperationClient';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'idClient',
        'idOperation',
        'dateheure',
    ];

    protected $useTimestamps = false;
}