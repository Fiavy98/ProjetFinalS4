<?php

namespace App\Controllers;

use App\Models\client;
use App\Models\commissionAutreOperateur;
use App\Models\frais;
use App\Models\gain;
use App\Models\historiqueGain;
use App\Models\historiqueOperationClient;
use App\Models\operateur;
use App\Models\operation;
use App\Models\typeOperation;

class ClientController extends BaseController
{
    protected client $clientModel;
    protected operation $operationModel;
    protected historiqueOperationClient $historyModel;
    protected historiqueGain $gainHistoryModel;
    protected gain $gainModel;
    protected frais $feeModel;
    protected typeOperation $typeOperationModel;
    protected operateur $operatorModel;
    protected operateur $promotion;
    protected commissionAutreOperateur $commissionModel;

    public function __construct()
    {
        $this->clientModel = new client();
        $this->operationModel = new operation();
        $this->historyModel = new historiqueOperationClient();
        $this->gainHistoryModel = new historiqueGain();
        $this->gainModel = new gain();
        $this->feeModel = new frais();
        $this->typeOperationModel = new typeOperation();
        $this->operatorModel = new operateur();
        $this->commissionModel = new commissionAutreOperateur();
    }

    public function index() { return $this->landing(); }
    public function landing() { return view('clients/landing'); }

    public function login()
    {
        helper(['form', 'url']);
        $defaults = $this->loginDefaults();
        if (! $this->request->is('post')) return view('clients/login', $defaults);
        $num = trim((string) $this->request->getPost('num'));
        $mdp = trim((string) $this->request->getPost('mdp'));
        $client = $this->clientModel->where('num', $num)->first();
        if ($client && (string) $client['mdp'] === $mdp) {
            session()->set(['client_id' => $client['id'], 'client_nom' => $client['nom']]);
            return redirect()->to('/client/operations');
        }
        return view('clients/login', ['error' => 'Numero ou mot de passe invalide.', 'defaultNum' => $num ?: $defaults['defaultNum'], 'defaultPassword' => $mdp ?: $defaults['defaultPassword']]);
    }

    public function logout() { session()->destroy(); return redirect()->to('/'); }

    public function operations()
    {
        $client = $this->requireClient();
        if (! $client) return redirect()->to('/client/login');
        return view('clients/operations', ['client' => $client, 'types' => $this->getTypeMap(), 'feeRules' => $this->getFeeRules(), 'operators' => $this->operatorModel->findAll(), 'commissionRules' => $this->commissionModel->findAll(), 'message' => session()->getFlashdata('message'), 'error' => session()->getFlashdata('error')]);
    }

    public function depot() { return $this->handleMoneyOperation('depot'); }
    public function retrait() { return $this->handleMoneyOperation('retrait'); }

    public function transfert()
    {
        $sender = $this->requireClient();
        if (! $sender) return redirect()->to('/client/login');
        if (! $this->request->is('post')) return redirect()->to('/client/operations');
        $amount = $this->postedAmount();
        $clientNum = trim((string) $this->request->getPost('client_num'));
        $destNum = trim((string) $this->request->getPost('dest_num'));
        $withFee = $this->includesFee();
        if ($amount === null) return $this->redirectWithError('Le montant du transfert doit être positif et valide.');
        if ($destNum === $sender['num']) return $this->redirectWithError('Un transfert vers votre propre numéro est impossible.');
        $destination = $this->clientModel->where('num', $destNum)->first();
        $envoie = $this->clientModel->where('num', $clientNum)->first();
        if (! $destination) return $this->redirectWithError('Le compte destination est introuvable.');
        $sourceOperator = $this->operatorForNumber($sender['num']);
        $destinationOperator = $this->operatorForNumber($destNum);
        if (! $sourceOperator || ! $destinationOperator) return $this->redirectWithError('Le préfixe de l’un des numéros est inconnu.');
        $typeId = $this->ensureTypeOperation('transfert');
        $fee = $this->feeFor($typeId, $amount);
        $normalFee = (float) ($fee['valeur'] ?? 0);
        $isExternal = (int) $sourceOperator['id'] !== (int) $destinationOperator['id'];
        $commission = $isExternal ? round($amount * $this->commissionModel->pourcentagePour((int) $destinationOperator['id'], $typeId) / 100, 2) : 0.0;
        $received = $withFee ? $amount : round($amount - $normalFee, 2);
        $debit = round($amount + ($withFee ? $normalFee : 0) + $commission, 2);
        if ($received <= 0) return $this->redirectWithError('Le montant reçu après déduction des frais doit être positif.');

        $db = db_connect(); $db->transStart();
        $freshSender = $this->clientModel->find($sender['id']);
        if ((float) $freshSender['solde'] < $debit) { $db->transRollback(); return $this->redirectWithError('Solde insuffisant pour effectuer le transfert, frais et commission inclus.'); }
        if($sourceOperator == $destinationOperator)
        {
            $promo = $this->promotion->where('idOperateur',$destinationOperator)->first();
            $vpromo = $this->promotion->getval($promo);
            $normalFee = $normalFee - $normalFee*$vpromo;

        }
        $operationId = $this->createTransferOperation($freshSender, $destination, $typeId, $fee['id'] ?? null, $received, (int) $sourceOperator['id'], (int) $destinationOperator['id'], $commission, $withFee, false);
        $this->clientModel->update($sender['id'], ['solde' => round((float) $freshSender['solde'] - $debit, 2)]);
        $freshDestination = $this->clientModel->find($destination['id']);
        $this->clientModel->update($destination['id'], ['solde' => round((float) $freshDestination['solde'] + $received, 2)]);
        $this->recordFeeGain($operationId, (int) $sourceOperator['id'], $normalFee);
        $db->transComplete();
        if (! $db->transStatus()) return $this->redirectWithError('Le transfert n’a pas pu être enregistré.');
        $message = 'Transfert effectué avec succès. Le destinataire reçoit ' . number_format($received, 2, ',', ' ') . ' Ar.';
        $message .= ' Total débité : ' . number_format($debit, 2, ',', ' ') . ' Ar (frais : ' . number_format($normalFee, 2, ',', ' ') . ' Ar, commission : ' . number_format($commission, 2, ',', ' ') . ' Ar).';
        return redirect()->to('/client/operations')->with('message', $message);
    }

    public function transfertMultiple()
    {
        $sender = $this->requireClient();
        if (! $sender) return redirect()->to('/client/login');
        if (! $this->request->is('post')) return redirect()->to('/client/operations');
        $total = $this->postedAmount(); $withFee = $this->includesFee();
        $rawNumbers = $this->request->getPost('dest_nums');
        $numbers = is_array($rawNumbers) ? $rawNumbers : explode(',', (string) $rawNumbers);
        $numbers = array_values(array_unique(array_filter(array_map(static fn ($number) => trim((string) $number), $numbers))));
        if ($total === null || empty($numbers)) return $this->redirectWithError('Saisissez un montant positif et au moins un destinataire unique.');
        $sourceOperator = $this->operatorForNumber($sender['num']);
        if (! $sourceOperator) return $this->redirectWithError('Le préfixe de votre numéro est inconnu.');
        $destinations = [];
        foreach ($numbers as $number) {
            if ($number === $sender['num']) return $this->redirectWithError('Votre propre numéro ne peut pas faire partie des destinataires.');
            $destination = $this->clientModel->where('num', $number)->first(); $destinationOperator = $this->operatorForNumber($number);
            if (! $destination || ! $destinationOperator) return $this->redirectWithError("Le compte ou le préfixe de {$number} est introuvable.");
            if ((int) $destinationOperator['id'] !== (int) $sourceOperator['id']) return $this->redirectWithError('Le transfert multiple est autorisé vers des numéros du même opérateur que l’expéditeur uniquement.');
            $destinations[] = $destination;
        }
        $typeId = $this->ensureTypeOperation('transfert'); $parts = $this->splitAmount($total, count($destinations)); $prepared = []; $totalDebit = 0.0;
        foreach ($destinations as $index => $destination) {
            $part = $parts[$index]; $fee = $this->feeFor($typeId, $part); $feeValue = (float) ($fee['valeur'] ?? 0); $received = $withFee ? $part : round($part - $feeValue, 2);
            if ($received <= 0) return $this->redirectWithError('Une part est inférieure ou égale aux frais applicables.');
            $prepared[] = compact('destination', 'fee', 'feeValue', 'received'); $totalDebit += $part + ($withFee ? $feeValue : 0);
        }
        $totalDebit = round($totalDebit, 2);
        $db = db_connect(); $db->transStart(); $freshSender = $this->clientModel->find($sender['id']);
        if ((float) $freshSender['solde'] < $totalDebit) { $db->transRollback(); return $this->redirectWithError('Solde insuffisant pour effectuer ce transfert multiple.'); }
        $this->clientModel->update($sender['id'], ['solde' => round((float) $freshSender['solde'] - $totalDebit, 2)]);
        foreach ($prepared as $transfer) {
            $operationId = $this->createTransferOperation($freshSender, $transfer['destination'], $typeId, $transfer['fee']['id'] ?? null, $transfer['received'], (int) $sourceOperator['id'], (int) $sourceOperator['id'], 0.0, $withFee, true);
            $freshDestination = $this->clientModel->find($transfer['destination']['id']);
            $this->clientModel->update($transfer['destination']['id'], ['solde' => round((float) $freshDestination['solde'] + $transfer['received'], 2)]);
            $this->recordFeeGain($operationId, (int) $sourceOperator['id'], $transfer['feeValue']);
        }
        $db->transComplete();
        if (! $db->transStatus()) return $this->redirectWithError('Le transfert multiple n’a pas pu être enregistré.');
        return redirect()->to('/client/operations')->with('message', count($prepared) . ' transferts internes effectués. Total débité : ' . number_format($totalDebit, 2, ',', ' ') . ' Ar.');
    }

    public function solde() { $client = $this->requireClient(); return $client ? view('clients/voirSolde', ['client' => $client]) : redirect()->to('/client/login'); }
    public function historique()
    {
        $client = $this->requireClient(); if (! $client) return redirect()->to('/client/login');
        $operations = $this->operationModel->builder()->select('operation.*, typeOperation.libele AS type_label')->join('typeOperation', 'typeOperation.id = operation.idTypeOperation', 'left')->join('historiqueOperationClient', 'historiqueOperationClient.idOperation = operation.id', 'left')->where('historiqueOperationClient.idClient', $client['id'])->orderBy('operation.dateheure', 'DESC')->get()->getResultArray();
        return view('clients/historique', ['client' => $client, 'operations' => $operations]);
    }

    private function handleMoneyOperation(string $label)
    {
        $client = $this->requireClient(); if (! $client) return redirect()->to('/client/login'); if (! $this->request->is('post')) return redirect()->to('/client/operations');
        $amount = $this->postedAmount(); if ($amount === null) return $this->redirectWithError('Le montant doit être positif et valide.');
        $operator = $this->operatorForNumber($client['num']); if (! $operator) return $this->redirectWithError('Le préfixe de votre numéro est inconnu.');
        $typeId = $this->ensureTypeOperation($label); $fee = $this->feeFor($typeId, $amount); $feeValue = (float) ($fee['valeur'] ?? 0); $debit = $label === 'retrait' ? round($amount + $feeValue, 2) : 0.0;
        $db = db_connect(); $db->transStart(); $fresh = $this->clientModel->find($client['id']);
        if ($label === 'retrait' && (float) $fresh['solde'] < $debit) { $db->transRollback(); return $this->redirectWithError('Solde insuffisant pour le retrait, frais inclus.'); }
        $operationId = $this->operationModel->insert(['idTypeOperation' => $typeId, 'idClient' => $client['id'], 'valeur' => $amount, 'idFrais' => $fee['id'] ?? null, 'idOperateurSource' => $operator['id'], 'commission' => 0, 'description' => ucfirst($label), 'dateheure' => date('Y-m-d H:i:s')], true);
        $newBalance = $label === 'depot' ? (float) $fresh['solde'] + $amount : (float) $fresh['solde'] - $debit;
        $this->clientModel->update($client['id'], ['solde' => round($newBalance, 2)]); $this->historyModel->insert(['idClient' => $client['id'], 'idOperation' => $operationId, 'dateheure' => date('Y-m-d H:i:s')]); $this->recordFeeGain($operationId, (int) $operator['id'], $feeValue);
        $db->transComplete(); if (! $db->transStatus()) return $this->redirectWithError('L’opération n’a pas pu être enregistrée.');
        $suffix = $label === 'retrait' ? ' Total débité : ' . number_format($debit, 2, ',', ' ') . ' Ar.' : '';
        return redirect()->to('/client/operations')->with('message', ucfirst($label) . ' effectué avec succès. Frais : ' . number_format($feeValue, 2, ',', ' ') . ' Ar.' . $suffix);
    }

    private function createTransferOperation(array $sender, array $destination, int $typeId, ?int $feeId, float $received, int $sourceOperatorId, int $destinationOperatorId, float $commission, bool $withFee, bool $multiple): int
    {
        $id = $this->operationModel->insert(['idTypeOperation' => $typeId, 'idClient' => $sender['id'], 'valeur' => $received, 'idFrais' => $feeId, 'idOperateurSource' => $sourceOperatorId, 'idOperateurDestinataire' => $destinationOperatorId, 'commission' => $commission, 'description' => ($multiple ? 'Transfert multiple' : 'Transfert') . ' vers ' . $destination['num'] . ($withFee ? ' (frais ajoutés)' : ' (frais déduits du reçu)'), 'dateheure' => date('Y-m-d H:i:s')], true);
        $this->historyModel->insert(['idClient' => $sender['id'], 'idOperation' => $id, 'dateheure' => date('Y-m-d H:i:s')]); return (int) $id;
    }
    private function recordFeeGain(int $operationId, int $operatorId, float $fee): void { if ($fee <= 0) return; $historyId = $this->gainHistoryModel->insert(['idOperation' => $operationId, 'valeur' => $fee, 'dateheure' => date('Y-m-d H:i:s')], true); $this->gainModel->insert(['idOperateur' => $operatorId, 'idHistorique' => $historyId, 'valeur' => $fee]); }
    private function operatorForNumber(string $number): ?array { foreach ($this->operatorModel->findAll() as $operator) foreach (explode(',', $operator['prefixes']) as $prefix) if (str_starts_with($number, trim($prefix))) return $operator; return null; }
    private function feeFor(int $typeId, float $amount): ?array { return $this->feeModel->where('idTypeOperation', $typeId)->where('min <=', $amount)->where('max >=', $amount)->orderBy('min', 'DESC')->first(); }
    private function splitAmount(float $total, int $count): array { $cents = (int) round($total * 100); $base = intdiv($cents, $count); $remainder = $cents % $count; $parts = []; for ($i = 0; $i < $count; $i++) $parts[] = ($base + ($i < $remainder ? 1 : 0)) / 100; return $parts; }
    private function postedAmount(): ?float { $value = filter_var($this->request->getPost('valeur'), FILTER_VALIDATE_FLOAT); return $value !== false && is_finite((float) $value) && $value > 0 ? round((float) $value, 2) : null; }
    private function includesFee(): bool { return $this->request->getPost('with_fee') === '1'; }
    private function requireClient(): ?array { $id = session()->get('client_id'); return $id ? $this->clientModel->find($id) : null; }
    private function redirectWithError(string $message) { return redirect()->to('/client/operations')->with('error', $message); }
    private function ensureTypeOperation(string $label): int { $existing = $this->typeOperationModel->where('libele', ucfirst($label))->first(); return $existing ? (int) $existing['id'] : (int) $this->typeOperationModel->insert(['libele' => ucfirst($label)], true); }
    private function loginDefaults(): array { $client = $this->clientModel->orderBy('id', 'ASC')->first(); return ['defaultNum' => $client['num'] ?? '', 'defaultPassword' => $client['mdp'] ?? '']; }
    private function getTypeMap(): array { $map = []; foreach ($this->typeOperationModel->findAll() as $type) $map[$type['libele']] = $type['id']; return $map; }
    private function getFeeRules(): array { return db_connect()->table('frais')->select('typeOperation.libele AS type_label, frais.min, frais.max, frais.valeur')->join('typeOperation', 'typeOperation.id = frais.idTypeOperation')->orderBy('typeOperation.libele')->orderBy('frais.min')->get()->getResultArray(); }
}
