<?php $currentPage = 'montants'; $pageTitle = 'Montants à envoyer'; require __DIR__ . '/_header.php'; ?>
<div class="yas-toolbar">
    <section class="yas-page-heading"><span class="yas-kicker">YAS · Règlements</span><h1>Montants à envoyer</h1><p>Total des transferts YAS à régler pour chaque autre opérateur.</p></section>
    <aside class="yas-total"><span>Total général</span><strong><?= number_format($totalGeneral, 2, ',', ' ') ?> Ar</strong></aside>
</div>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Opérateur destinataire</th><th>Nombre de transferts</th><th>Montant total à envoyer</th></tr></thead><tbody>
<?php if (empty($montants)): ?><tr><td colspan="3">Aucun transfert externe à régler.</td></tr><?php else: foreach ($montants as $montant): ?><tr><td><?= esc($montant['nom']) ?></td><td><?= esc($montant['nombre_transferts']) ?></td><td><?= number_format((float) $montant['montant_total'], 2, ',', ' ') ?> Ar</td></tr><?php endforeach; endif; ?>
</tbody></table></section>
<?php require __DIR__ . '/_footer.php'; ?>
