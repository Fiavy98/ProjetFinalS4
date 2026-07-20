<?php

namespace App\Models;

use CodeIgniter\Model;


class gain extends Model
{

    protected $table='gain';

    protected $primaryKey='id';



    public function historiqueGain()
    {

        return $this->select(
            'gain.*,
            historiqueGain.dateheure,
            operation.description,
            typeOperation.libele'
        )

        ->join(
            'historiqueGain',
            'historiqueGain.id= gain.idHistorique'
        )

        ->join(
            'operation',
            'operation.id=historiqueGain.idOperation'
        )

        ->join(
            'typeOperation',
            'typeOperation.id=operation.idTypeOperation'
        )

        ->findAll();

    }

}