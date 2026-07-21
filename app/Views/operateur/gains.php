<?php $currentPage = 'gains'; $pageTitle = 'Historique des gains'; require __DIR__ . '/_header.php'; ?>
<div class="yas-toolbar"><section class="yas-page-heading"><span class="yas-kicker">YAS · Revenus</span><h1>Historique des gains</h1><p>Gains générés par les opérations effectuées.</p></section><aside class="yas-total"><span>Total des gains</span><strong><?= number_format($totalGains, 2, ',', ' ') ?> Ar</strong></aside></div>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Date</th><th>Opération</th><th>Gain</th></tr></thead><tbody>
<?php foreach ($gains as $g): ?><tr><td><?= esc($g['dateheure']) ?></td><td><?= esc($g['libele']) ?></td><td><?= esc($g['valeur']) ?> Ar</td></tr><?php endforeach; ?>
</tbody></table></section>
<?php require __DIR__ . '/_footer.php'; ?>
