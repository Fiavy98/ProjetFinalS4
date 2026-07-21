<?php $currentPage = 'gains'; $pageTitle = 'Historique des gains'; require __DIR__ . '/_header.php'; ?>
<section class="yas-page-heading"><span class="yas-kicker">YAS · Revenus</span><h1>Historique des gains</h1><p>Gains générés par les opérations effectuées.</p></section>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Date</th><th>Opération</th><th>Gain</th></tr></thead><tbody>
<?php foreach ($gains as $g): ?><tr><td><?= esc($g['dateheure']) ?></td><td><?= esc($g['libele']) ?></td><td><?= esc($g['valeur']) ?></td></tr><?php endforeach; ?>
</tbody></table></section>
<?php require __DIR__ . '/_footer.php'; ?>
