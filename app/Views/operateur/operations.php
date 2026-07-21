<?php $currentPage = 'operations'; $pageTitle = 'Opérations'; require __DIR__ . '/_header.php'; ?>
<section class="yas-page-heading"><span class="yas-kicker">YAS · Suivi</span><h1>Liste des opérations</h1><p>Historique des transactions des clients.</p></section>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Client</th><th>Numéro</th><th>Type</th><th>Montant</th><th>Frais</th><th>Date</th></tr></thead><tbody>
<?php foreach ($operations as $op): ?><tr><td><?= esc($op['client']) ?></td><td><?= esc($op['num']) ?></td><td><?= esc($op['libele']) ?></td><td><?= esc($op['valeur']) ?></td><td><?= esc($op['frais'] ?? 0) ?></td><td><?= esc($op['dateheure']) ?></td></tr><?php endforeach; ?>
</tbody></table></section>
<?php require __DIR__ . '/_footer.php'; ?>
