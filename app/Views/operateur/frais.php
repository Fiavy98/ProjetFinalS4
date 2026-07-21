<?php $currentPage = 'frais'; $pageTitle = 'Configuration des frais'; require __DIR__ . '/_header.php'; ?>
<section class="yas-page-heading"><span class="yas-kicker">YAS · Tarification</span><h1>Configuration des frais</h1><p>Grille des frais appliqués aux opérations.</p></section>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Opération</th><th>Minimum</th><th>Maximum</th><th>Frais</th></tr></thead><tbody>
<?php foreach ($frais as $f): ?><tr><td><?= esc($f['libele']) ?></td><td><?= esc($f['min']) ?></td><td><?= esc($f['max']) ?></td><td><?= esc($f['valeur']) ?></td></tr><?php endforeach; ?>
</tbody></table></section>
<?php require __DIR__ . '/_footer.php'; ?>
