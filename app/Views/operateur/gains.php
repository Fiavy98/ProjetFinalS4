<?php $currentPage = 'gains'; $pageTitle = 'Situation des gains'; require __DIR__ . '/_header.php'; ?>
<div class="yas-toolbar">
    <section class="yas-page-heading"><span class="yas-kicker">YAS · Revenus</span><h1>Situation des gains</h1><p>Frais acquis par YAS et commissions reçues par les autres opérateurs.</p></section>
    <aside class="yas-total"><span>Gains YAS</span><strong><?= number_format($totalGains, 2, ',', ' ') ?> Ar</strong></aside>
</div>
<section class="yas-page-heading"><h2>Gains de YAS via les frais</h2></section>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Date</th><th>Type d’opération</th><th>Montant du frais</th></tr></thead><tbody>
<?php if (empty($gains)): ?><tr><td colspan="3">Aucun gain enregistré.</td></tr><?php else: foreach ($gains as $gain): ?><tr><td><?= esc($gain['dateheure']) ?></td><td><?= esc($gain['libele']) ?></td><td><?= number_format((float) $gain['valeur'], 2, ',', ' ') ?> Ar</td></tr><?php endforeach; endif; ?>
</tbody></table></section>
<section class="yas-page-heading"><h2>Commissions des autres opérateurs</h2><p>Chaque commission est liée à un transfert YAS vers un autre réseau.</p></section>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Opérateur</th><th>Date</th><th>Type d’opération</th><th>Commission reçue</th></tr></thead><tbody>
<?php if (empty($commissions)): ?><tr><td colspan="4">Aucune commission externe enregistrée.</td></tr><?php else: foreach ($commissions as $commission): ?><tr><td><?= esc($commission['operateur']) ?></td><td><?= esc($commission['dateheure']) ?></td><td><?= esc($commission['libele']) ?></td><td><?= number_format((float) $commission['commission'], 2, ',', ' ') ?> Ar</td></tr><?php endforeach; endif; ?>
</tbody></table></section>
<section class="yas-page-heading"><h2>Total des commissions par opérateur</h2></section>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Opérateur</th><th>Total des commissions</th></tr></thead><tbody>
<?php if (empty($totauxCommissions)): ?><tr><td colspan="2">Aucune commission externe enregistrée.</td></tr><?php else: foreach ($totauxCommissions as $total): ?><tr><td><?= esc($total['operateur']) ?></td><td><?= number_format((float) $total['total_commission'], 2, ',', ' ') ?> Ar</td></tr><?php endforeach; endif; ?>
</tbody></table></section>
<?php require __DIR__ . '/_footer.php'; ?>
