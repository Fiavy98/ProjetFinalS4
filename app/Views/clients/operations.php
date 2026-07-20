<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations client</title>
    <link rel="stylesheet" href="/assets/client.css">
</head>
<body class="client-page">
    <div class="page-shell">
        <header class="brandbar">
            <div class="brand">
                <div class="brand-mark">MM</div>
                <div>
                    <div>Bonjour <?= esc($client['nom']) ?></div>
                    <small class="muted">Compte <?= esc($client['num']) ?> · Solde <?= number_format((float) $client['solde'], 2, ',', ' ') ?></small>
                </div>
            </div>
            <nav class="nav-links">
                <a class="chip" href="/client/solde">Voir solde</a>
                <a class="chip" href="/client/historique">Historique</a>
                <a class="chip" href="/client/logout">Deconnexion</a>
            </nav>
        </header>

        <?php if (! empty($message)) : ?>
            <div class="alert alert-success"><?= esc($message) ?></div>
        <?php endif; ?>

        <?php if (! empty($error)) : ?>
            <div class="alert alert-error"><?= esc($error) ?></div>
        <?php endif; ?>

        <section class="section-grid">
            <div class="form-card">
                <h2>Faire un depot</h2>
                <p class="muted">Ajouter de l'argent à votre compte Mobile Money.</p>
                <form method="post" action="/client/depot" class="form-grid">
                    <div>
                        <label for="depot_valeur">Montant</label>
                        <input id="depot_valeur" name="valeur" type="number" step="0.01" min="0.01" placeholder="10000" required>
                    </div>
                    <button class="btn" type="submit">Deposer</button>
                </form>
            </div>

            <div class="form-card">
                <h2>Faire un retrait</h2>
                <p class="muted">Retirer un montant depuis votre solde disponible.</p>
                <form method="post" action="/client/retrait" class="form-grid">
                    <div>
                        <label for="retrait_valeur">Montant</label>
                        <input id="retrait_valeur" name="valeur" type="number" step="0.01" min="0.01" placeholder="5000" required>
                    </div>
                    <button class="btn" type="submit">Retirer</button>
                </form>
            </div>

            <div class="form-card">
                <h2>Faire un transfert</h2>
                <p class="muted">Envoyer de l'argent vers un autre compte enregistré.</p>
                <form method="post" action="/client/transfert" class="form-grid">
                    <div>
                        <label for="dest_num">Numero destinataire</label>
                        <input id="dest_num" name="dest_num" type="text" placeholder="0339876543" required>
                    </div>

                    <div>
                        <label for="transfert_valeur">Montant</label>
                        <input id="transfert_valeur" name="valeur" type="number" step="0.01" min="0.01" placeholder="25000" required>
                    </div>

                    <div>
                        <label for="with_fee">Frais</label>
                        <select id="with_fee" name="with_fee">
                            <option value="0">Sans frais</option>
                            <option value="1">Avec frais</option>
                        </select>
                    </div>

                    <button class="btn" type="submit">Transferrer</button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>