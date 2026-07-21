<?php $currentPage = 'frais'; $pageTitle = 'Modifier une tranche de frais'; require __DIR__ . '/_header.php'; ?>
<section class="yas-page-heading"><span class="yas-kicker">YAS · Tarification</span><h1>Modifier une tranche</h1><p>Mettez à jour les montants et le type d’opération.</p></section>
<section class="yas-card yas-form-card">
    <form method="post" action="/operateur/frais/<?= esc($frais['id']) ?>/modifier">
        <div class="yas-form-grid">
            <div class="yas-field"><label for="type">Type d’opération</label><select id="type" name="idTypeOperation" required><?php foreach ($typesOperation as $type): ?><option value="<?= esc($type['id']) ?>" <?= (int) $type['id'] === (int) $frais['idTypeOperation'] ? 'selected' : '' ?>><?= esc($type['libele']) ?></option><?php endforeach; ?></select></div>
            <div class="yas-field"><label for="valeur">Frais</label><input id="valeur" name="valeur" type="number" min="0" step="0.01" value="<?= esc($frais['valeur']) ?>" required></div>
            <div class="yas-field"><label for="min">Montant minimum</label><input id="min" name="min" type="number" min="0" step="0.01" value="<?= esc($frais['min']) ?>" required></div>
            <div class="yas-field"><label for="max">Montant maximum</label><input id="max" name="max" type="number" min="0" step="0.01" value="<?= esc($frais['max']) ?>" required></div>
        </div>
        <div class="yas-form-actions"><button class="yas-button" type="submit">Enregistrer</button><a class="yas-action yas-cancel" href="/operateur/frais">Annuler</a></div>
    </form>
</section>
<?php require __DIR__ . '/_footer.php'; ?>
