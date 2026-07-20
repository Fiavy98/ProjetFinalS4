<?php

namespace App\Models;

use CodeIgniter\Model;

class operateur extends Model
{
    protected $table = 'operateur';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom',
        'prefixes'
    ];

    protected $useTimestamps = false;
}