<?php $currentPage = 'frais'; $pageTitle = 'Configuration des frais'; require __DIR__ . '/_header.php'; ?>
<div class="yas-toolbar">
    <section class="yas-page-heading"><span class="yas-kicker">YAS · Tarification</span><h1>Configuration des frais</h1><p>Ajoutez, modifiez ou supprimez les tranches de frais appliquées.</p></section>
</div>
<section class="yas-card yas-form-card">
    <h2>Ajouter une tranche</h2>
    <form method="post" action="/operateur/frais">
        <div class="yas-form-grid">
            <div class="yas-field"><label for="type">Type d’opération</label><select id="type" name="idTypeOperation" required><option value="">Choisir un type</option><?php foreach ($typesOperation as $type): ?><option value="<?= esc($type['id']) ?>"><?= esc($type['libele']) ?></option><?php endforeach; ?></select></div>
            <div class="yas-field"><label for="valeur">Frais</label><input id="valeur" name="valeur" type="number" min="0" step="0.01" required></div>
            <div class="yas-field"><label for="min">Montant minimum</label><input id="min" name="min" type="number" min="0" step="0.01" required></div>
            <div class="yas-field"><label for="max">Montant maximum</label><input id="max" name="max" type="number" min="0" step="0.01" required></div>
        </div>
        <div class="yas-form-actions"><button class="yas-button" type="submit">Ajouter la tranche</button></div>
    </form>
</section>
<section class="yas-page-heading"><h2>Tranches existantes</h2></section>
<section class="yas-table-card"><table class="yas-table"><thead><tr><th>Opération</th><th>Minimum</th><th>Maximum</th><th>Frais</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($frais as $f): ?><tr><td><?= esc($f['libele']) ?></td><td><?= esc($f['min']) ?></td><td><?= esc($f['max']) ?></td><td><?= esc($f['valeur']) ?></td><td><div class="yas-row-actions"><a class="yas-button" href="/operateur/frais/<?= esc($f['id']) ?>/modifier">Modifier</a><form method="post" action="/operateur/frais/<?= esc($f['id']) ?>/supprimer" onsubmit="return confirm('Supprimer cette tranche de frais ?');"><button class="yas-button yas-button-danger" type="submit">Supprimer</button></form></div></td></tr><?php endforeach; ?>
</tbody></table></section>
<?php require __DIR__ . '/_footer.php'; ?>
