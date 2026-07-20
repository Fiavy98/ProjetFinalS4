<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique client</title>
    <link rel="stylesheet" href="/assets/client.css">
</head>
<body class="client-page">
    <div class="table-layout page-shell">
        <section class="table-card" style="width: min(100%, 1080px);">
            <div class="brand" style="margin-bottom: 16px;">
                <div class="brand-mark">MM</div>
                <div>
                    <div>Historique client</div>
                    <small class="muted"><?= esc($client['nom']) ?> · <?= esc($client['num']) ?></small>
                </div>
            </div>

            <div class="hero-actions" style="margin-top: 0; margin-bottom: 8px;">
                <a class="btn" href="/client/operations">Retour aux operations</a>
                <a class="btn-secondary" href="/client/solde">Voir solde</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($operations)) : ?>
                            <tr><td colspan="4">Aucune operation.</td></tr>
                        <?php else : ?>
                            <?php foreach ($operations as $operation) : ?>
                                <tr>
                                    <td><?= esc($operation['dateheure']) ?></td>
                                    <td><?= esc($operation['type_label'] ?? '') ?></td>
                                    <td><?= number_format((float) $operation['valeur'], 2, ',', ' ') ?></td>
                                    <td><?= esc($operation['description'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>