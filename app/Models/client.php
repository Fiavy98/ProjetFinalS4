<?php

namespace App\Models;

use CodeIgniter\Model;

class client extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'num',
        'mdp',
        'nom',
        'solde'
    ];

    protected $useTimestamps = false;
}