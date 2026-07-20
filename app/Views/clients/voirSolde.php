<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solde client</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 0; }
        .wrap { max-width: 700px; margin: 8vh auto; background: white; border-radius: 18px; padding: 28px; box-shadow: 0 12px 35px rgba(0,0,0,.08); }
        .amount { font-size: 3rem; font-weight: 800; margin: 18px 0; color: #0f62fe; }
        a { color: #0f62fe; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Solde de <?= esc($client['nom']) ?></h1>
        <p>Numero: <?= esc($client['num']) ?></p>
        <div class="amount"><?= number_format((float) $client['solde'], 2, ',', ' ') ?></div>
        <a href="/client/operations">Retour aux operations</a>
    </div>
</body>
</html>