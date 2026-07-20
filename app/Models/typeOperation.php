<?php

namespace App\Models;

use CodeIgniter\Model;

class typeOperation extends Model
{
    protected $table = 'typeOperation';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'libele'
    ];

    protected $useTimestamps = false;
}