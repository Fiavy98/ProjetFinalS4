<?php

namespace App\Controllers;

use App\Models\client;
use App\Models\historiqueOperationClient;
use App\Models\operation;
use App\Models\typeOperation;

class ClientController extends BaseController
{
    protected client $clientModel;
    protected operation $operationModel;
    protected historiqueOperationClient $historyModel;
    protected typeOperation $typeOperationModel;

    public function __construct()
    {
        $this->clientModel = new client();
        $this->operationModel = new operation();
        $this->historyModel = new historiqueOperationClient();
        $this->typeOperationModel = new typeOperation();
    }

    public function index()
    {
        return $this->landing();
    }

    public function landing()
    {
        return view('clients/landing');
    }

    public function login()
    {
        helper(['form', 'url']);
        $defaults = $this->loginDefaults();

        if ($this->request->is('post')) {
            $num = trim((string) $this->request->getPost('num'));
            $mdp = trim((string) $this->request->getPost('mdp'));

            $client = $this->clientModel->where('num', $num)->first();

            if ($client && (string) $client['mdp'] === $mdp) {
                session()->set('client_id', $client['id']);
                session()->set('client_nom', $client['nom']);
                return redirect()->to('/client/operations');
            }

            return view('clients/login', [
                'error' => 'Numero ou mot de passe invalide.',
                'defaultNum' => $num !== '' ? $num : $defaults['defaultNum'],
                'defaultPassword' => $mdp !== '' ? $mdp : $defaults['defaultPassword'],
            ]);
        }

        return view('clients/login', $defaults);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/client/login');
    }

    public function operations()
    {
        $client = $this->requireClient();
        if ($client === null) {
            return redirect()->to('/client/login');
        }

        return view('clients/operations', [
            'client' => $client,
            'types' => $this->getTypeMap(),
            'feeRules' => $this->getFeeRules(),
            'message' => session()->getFlashdata('message'),
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function depot()
    {
        return $this->handleMoneyOperation('depot');
    }

    public function retrait()
    {
        return $this->handleMoneyOperation('retrait');
    }

    public function transfert()
    {
        $client = $this->requireClient();
        if ($client === null) {
            return redirect()->to('/client/login');
        }

        helper(['form', 'url']);
            if (! $this->request->is('post')) {
            return redirect()->to('/client/operations');
        }

        $destNum = trim((string) $this->request->getPost('dest_num'));
        $amount = (float) $this->request->getPost('valeur');
        $withFee = (bool) $this->request->getPost('with_fee');

        if ($amount <= 0) {
            return $this->redirectWithError('Le montant du transfert doit etre positif.');
        }

        $destination = $this->clientModel->where('num', $destNum)->first();
        if (! $destination) {
            return $this->redirectWithError('Le compte destination est introuvable.');
        }

        $typeId = $this->ensureTypeOperation('transfert');
        $feeValue = $withFee ? $this->resolveFeeValue($typeId, $amount) : 0.0;
        $totalDebit = $amount + $feeValue;

        $db = db_connect();
        $db->transStart();

        $freshClient = $this->clientModel->find($client['id']);
        if ((float) $freshClient['solde'] < $totalDebit) {
            $db->transRollback();
            return $this->redirectWithError('Solde insuffisant pour effectuer le transfert.');
        }

        $operationId = $this->operationModel->insert([
            'idTypeOperation' => $typeId,
            'idClient' => $client['id'],
            'valeur' => $amount,
            'idFrais' => null,
            'description' => 'Transfert vers ' . $destination['num'] . ($withFee ? ' avec frais' : ' sans frais'),
            'dateheure' => date('Y-m-d H:i:s'),
        ], true);

        $this->clientModel->update($client['id'], [
            'solde' => (float) $freshClient['solde'] - $totalDebit,
        ]);

        $destinationFresh = $this->clientModel->find($destination['id']);
        $this->clientModel->update($destination['id'], [
            'solde' => (float) $destinationFresh['solde'] + $amount,
        ]);

        $this->historyModel->insert([
            'idClient' => $client['id'],
            'idOperation' => $operationId,
            'dateheure' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        return redirect()->to('/client/operations')->with('message', 'Transfert effectue avec succes.');
    }

    public function solde()
    {
        $client = $this->requireClient();
        if ($client === null) {
            return redirect()->to('/client/login');
        }

        return view('clients/voirSolde', [
            'client' => $client,
        ]);
    }

    public function historique()
    {
        $client = $this->requireClient();
        if ($client === null) {
            return redirect()->to('/client/login');
        }

        $builder = $this->operationModel->builder();
        $operations = $builder
            ->select('operation.*, typeOperation.libele AS type_label')
            ->join('typeOperation', 'typeOperation.id = operation.idTypeOperation', 'left')
            ->join('historiqueOperationClient', 'historiqueOperationClient.idOperation = operation.id', 'left')
            ->where('historiqueOperationClient.idClient', $client['id'])
            ->orderBy('operation.dateheure', 'DESC')
            ->get()
            ->getResultArray();

        return view('clients/historique', [
            'client' => $client,
            'operations' => $operations,
        ]);
    }

    private function handleMoneyOperation(string $typeLabel)
    {
        $client = $this->requireClient();
        if ($client === null) {
            return redirect()->to('/client/login');
        }

        helper(['form', 'url']);

            if (! $this->request->is('post')) {
            return redirect()->to('/client/operations');
        }

        $amount = (float) $this->request->getPost('valeur');
        if ($amount <= 0) {
            return $this->redirectWithError('Le montant doit etre positif.');
        }

        $typeId = $this->ensureTypeOperation($typeLabel);
        $current = $this->clientModel->find($client['id']);

        if ($typeLabel === 'retrait' && (float) $current['solde'] < $amount) {
            return $this->redirectWithError('Solde insuffisant pour le retrait.');
        }

        $newBalance = $typeLabel === 'depot'
            ? (float) $current['solde'] + $amount
            : (float) $current['solde'] - $amount;

        $operationId = $this->operationModel->insert([
            'idTypeOperation' => $typeId,
            'idClient' => $client['id'],
            'valeur' => $amount,
            'idFrais' => null,
            'description' => ucfirst($typeLabel),
            'dateheure' => date('Y-m-d H:i:s'),
        ], true);

        $this->clientModel->update($client['id'], [
            'solde' => $newBalance,
        ]);

        $this->historyModel->insert([
            'idClient' => $client['id'],
            'idOperation' => $operationId,
            'dateheure' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/client/operations')->with('message', ucfirst($typeLabel) . ' effectue avec succes.');
    }

    private function requireClient(): ?array
    {
        $clientId = session()->get('client_id');
        if (! $clientId) {
            return null;
        }

        return $this->clientModel->find($clientId);
    }

    private function redirectWithError(string $message)
    {
        return redirect()->to('/client/operations')->with('error', $message);
    }

    private function ensureTypeOperation(string $label): int
    {
        $existing = $this->typeOperationModel->where('libele', $label)->first();
        if ($existing) {
            return (int) $existing['id'];
        }

        return (int) $this->typeOperationModel->insert(['libele' => $label], true);
    }

    private function loginDefaults(): array
    {
        $client = $this->clientModel->orderBy('id', 'ASC')->first();

        return [
            'defaultNum' => $client['num'] ?? '',
            'defaultPassword' => $client['mdp'] ?? '',
        ];
    }

    private function getTypeMap(): array
    {
        $types = $this->typeOperationModel->findAll();
        $map = [];
        foreach ($types as $type) {
            $map[$type['libele']] = $type['id'];
        }

        return $map;
    }

    private function resolveFeeValue(int $typeId, float $amount): float
    {
        $fee = db_connect()->table('frais')
            ->where('idTypeOperation', $typeId)
            ->where('min <=', $amount)
            ->where('max >=', $amount)
            ->get()
            ->getFirstRow('array');

        return $fee ? (float) $fee['valeur'] : 0.0;
    }
    
    public function transfertMultiple()
    {
        $client = $this->requireClient();
        if ($client === null) {
            return redirect()->to('/client/login');
        }

        helper(['form', 'url']);
        if (! $this->request->is('post')) {
            return redirect()->to('/client/operations');
        }

        $destNumsInput = $this->request->getPost('dest_nums');
        $totalAmount = (float) $this->request->getPost('valeur');
        $withFee = (bool) $this->request->getPost('with_fee');

        if ($totalAmount <= 0) {
            return $this->redirectWithError('Le montant du transfert doit être positif.');
        }

        if (is_array($destNumsInput)) {
            $destNums = array_values(array_filter(array_map('trim', $destNumsInput)));
        } else {
            $destNums = array_values(array_filter(array_map('trim', explode(',', (string) $destNumsInput))));
        }

        if (empty($destNums)) {
            return $this->redirectWithError('Veuillez spécifier au moins un numéro de destination.');
        }

        $count = count($destNums);
        $amountPerNum = $totalAmount / $count;

        // Récupération des opérateurs pour la vérification des préfixes
        $operators = db_connect()->table('operateur')->get()->getResultArray();
        
        $getOperatorId = function (string $num) use ($operators) {
            $prefix3 = substr($num, 0, 3);
            foreach ($operators as $op) {
                $prefixes = explode(',', $op['prefixes']);
                if (in_array($prefix3, $prefixes)) {
                    return (int) $op['id'];
                }
            }
            return null;
        };

        $firstOpId = null;
        $destinations = [];

        // Validation des comptes et de la contrainte "même opérateur uniquement"
        foreach ($destNums as $num) {
            $destination = $this->clientModel->where('num', $num)->first();
            if (! $destination) {
                return $this->redirectWithError("Le compte destination {$num} est introuvable.");
            }

            $opId = $getOperatorId($num);
            if ($opId === null) {
                return $this->redirectWithError("L'opérateur du numéro {$num} n'est pas reconnu.");
            }

            if ($firstOpId === null) {
                $firstOpId = $opId;
            } elseif ($firstOpId !== $opId) {
                return $this->redirectWithError("Tous les numéros doivent appartenir au même opérateur.");
            }

            $destinations[] = $destination;
        }

        // Calcul des frais par transaction individuelle et débit total
        $typeId = $this->ensureTypeOperation('transfert');
        $feePerTransfer = $withFee ? $this->resolveFeeValue($typeId, $amountPerNum) : 0.0;
        $totalDebit = ($amountPerNum + $feePerTransfer) * $count;

        $db = db_connect();
        $db->transStart();

        $freshClient = $this->clientModel->find($client['id']);
        if ((float) $freshClient['solde'] < $totalDebit) {
            $db->transRollback();
            return $this->redirectWithError('Solde insuffisant pour effectuer ce transfert multiple.');
        }

        // Débit du compte émetteur
        $this->clientModel->update($client['id'], [
            'solde' => (float) $freshClient['solde'] - $totalDebit,
        ]);

        // Crédit de chaque compte destinataire et enregistrement des historiques
        foreach ($destinations as $destination) {
            $operationId = $this->operationModel->insert([
                'idTypeOperation' => $typeId,
                'idClient' => $client['id'],
                'valeur' => $amountPerNum,
                'idFrais' => null,
                'description' => 'Transfert multiple vers ' . $destination['num'] . ($withFee ? ' avec frais' : ' sans frais'),
                'dateheure' => date('Y-m-d H:i:s'),
            ], true);

            $destinationFresh = $this->clientModel->find($destination['id']);
            $this->clientModel->update($destination['id'], [
                'solde' => (float) $destinationFresh['solde'] + $amountPerNum,
            ]);

            $this->historyModel->insert([
                'idClient' => $client['id'],
                'idOperation' => $operationId,
                'dateheure' => date('Y-m-d H:i:s'),
            ]);
        }

        $db->transComplete();

        $message = 'Transfert multiple effectué avec succès.';
        if ($withFee) {
            $message .= ' Frais total estimé: ' . number_format($feePerTransfer * $count, 2, ',', ' ') . '.';
        }

        return redirect()->to('/client/operations')->with('message', $message);
    }

    private function getFeeRules(): array
    {
        return db_connect()->table('frais')
            ->select('typeOperation.libele AS type_label, frais.min, frais.max, frais.valeur')
            ->join('typeOperation', 'typeOperation.id = frais.idTypeOperation')
            ->orderBy('typeOperation.libele', 'ASC')
            ->orderBy('frais.min', 'ASC')
            ->get()
            ->getResultArray();
    }

}