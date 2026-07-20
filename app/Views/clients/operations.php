<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations client</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #e8f0ff, #f8fafc); margin: 0; padding: 0; color: #102a43; }
        .wrap { max-width: 960px; margin: 5vh auto; padding: 24px; }
        .top { display:flex; justify-content:space-between; gap: 12px; align-items:center; flex-wrap: wrap; margin-bottom: 20px; }
        .card { background: white; border-radius: 18px; padding: 22px; box-shadow: 0 12px 35px rgba(16,42,67,.08); margin-bottom: 18px; }
        .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
        input, select { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #cbd2d9; border-radius: 10px; margin-top: 8px; }
        button { padding: 12px 16px; border: 0; border-radius: 10px; background: #0f62fe; color: white; font-weight: 700; cursor: pointer; }
        .secondary { background: #334e68; text-decoration:none; display:inline-block; }
        .success { background: #ecfdf3; color: #067647; padding: 12px; border-radius: 10px; margin-bottom: 14px; }
        .error { background: #fdecea; color: #b42318; padding: 12px; border-radius: 10px; margin-bottom: 14px; }
        .meta { color:#486581; margin: 0; }
        .actions { display:flex; gap: 10px; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <div>
                <h1>Bonjour <?= esc($client['nom']) ?></h1>
                <p class="meta">Compte: <?= esc($client['num']) ?> | Solde actuel: <?= number_format((float) $client['solde'], 2, ',', ' ') ?></p>
            </div>
            <div class="actions">
                <a class="secondary" href="/client/solde">Voir solde</a>
                <a class="secondary" href="/client/historique">Historique</a>
                <a class="secondary" href="/client/logout">Deconnexion</a>
            </div>
        </div>

        <?php if (! empty($message)) : ?>
            <div class="success"><?= esc($message) ?></div>
        <?php endif; ?>

        <?php if (! empty($error)) : ?>
            <div class="error"><?= esc($error) ?></div>
        <?php endif; ?>

        <div class="grid">
            <div class="card">
                <h2>Faire un depot</h2>
                <form method="post" action="/client/depot">
                    <label for="depot_valeur">Montant</label>
                    <input id="depot_valeur" name="valeur" type="number" step="0.01" min="0.01" required>
                    <button type="submit">Deposer</button>
                </form>
            </div>

            <div class="card">
                <h2>Faire un retrait</h2>
                <form method="post" action="/client/retrait">
                    <label for="retrait_valeur">Montant</label>
                    <input id="retrait_valeur" name="valeur" type="number" step="0.01" min="0.01" required>
                    <button type="submit">Retirer</button>
                </form>
            </div>

            <div class="card">
                <h2>Faire un transfert</h2>
                <form method="post" action="/client/transfert">
                    <label for="dest_num">Numero destinataire</label>
                    <input id="dest_num" name="dest_num" type="text" required>

                    <label for="transfert_valeur">Montant</label>
                    <input id="transfert_valeur" name="valeur" type="number" step="0.01" min="0.01" required>

                    <label for="with_fee">Frais</label>
                    <select id="with_fee" name="with_fee">
                        <option value="0">Sans frais</option>
                        <option value="1">Avec frais</option>
                    </select>

                    <button type="submit">Transferrer</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>