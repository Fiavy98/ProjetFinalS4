<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solde client</title>
    <link rel="stylesheet" href="/assets/client.css">
</head>
<body class="client-page">
    <div class="balance-layout page-shell">
        <section class="balance-card">
            <div class="brand" style="margin-bottom: 16px;">
                <div class="brand-mark">MM</div>
                <div>
                    <div>Solde client</div>
                    <small class="muted">Consultation du compte</small>
                </div>
            </div>
            <h1 class="page-title"><?= esc($client['nom']) ?></h1>
            <p class="muted">Numero: <?= esc($client['num']) ?></p>
            <div class="amount"><?= number_format((float) $client['solde'], 2, ',', ' ') ?></div>
            <div class="hero-actions">
                <a class="btn" href="/client/operations">Retour aux operations</a>
                <a class="btn-secondary" href="/client/historique">Voir historique</a>
            </div>
        </section>
    </div>
</body>
</html>