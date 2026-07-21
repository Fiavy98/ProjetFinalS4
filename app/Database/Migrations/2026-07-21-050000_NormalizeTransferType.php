<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeTransferType extends Migration
{
    public function up()
    {
        $canonical = $this->db->table('typeOperation')
            ->where('libele', 'Transfert')
            ->get()->getRowArray();
        if (! $canonical) {
            return;
        }

        $duplicates = $this->db->query("SELECT id FROM typeOperation WHERE LOWER(libele) = 'transfert' AND id != ?", [$canonical['id']])->getResultArray();
        foreach ($duplicates as $duplicate) {
            $this->db->table('operation')->where('idTypeOperation', $duplicate['id'])->update(['idTypeOperation' => $canonical['id']]);
            $this->db->table('commissionAutreOperateur')->where('idTypeOperation', $duplicate['id'])->update(['idTypeOperation' => $canonical['id']]);
            $this->db->table('typeOperation')->where('id', $duplicate['id'])->delete();
        }
    }

    public function down()
    {
        // This migration only repairs duplicated legacy labels.
    }
}
