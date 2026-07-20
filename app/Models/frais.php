<?php

namespace App\Models;

use CodeIgniter\Model;


class frais extends Model
{
    protected $table = 'frais';

    protected $primaryKey='id';


    public function listeFrais()
    {
        return $this->select(
            'frais.*,
            typeOperation.libele'
        )
        ->join(
            'typeOperation',
            'typeOperation.id=frais.idTypeOperation'
        )
        ->findAll();
    }
}