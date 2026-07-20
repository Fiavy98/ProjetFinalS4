<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique client</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 0; }
        .wrap { max-width: 1000px; margin: 5vh auto; background: white; border-radius: 18px; padding: 28px; box-shadow: 0 12px 35px rgba(0,0,0,.08); }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { text-align:left; padding: 12px 10px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; }
        a { color: #0f62fe; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Historique de <?= esc($client['nom']) ?></h1>
        <p><a href="/client/operations">Retour aux operations</a></p>

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
</body>
</html>