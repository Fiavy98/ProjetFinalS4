<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion client</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f5f7; margin: 0; padding: 0; }
        .wrap { max-width: 420px; margin: 8vh auto; background: white; padding: 28px; border-radius: 16px; box-shadow: 0 12px 30px rgba(0,0,0,.08); }
        h1 { margin-top: 0; }
        label { display:block; margin: 14px 0 6px; }
        input { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #cfd6dd; border-radius: 10px; }
        button { width: 100%; margin-top: 18px; padding: 12px; border: 0; border-radius: 10px; background: #0f62fe; color: white; font-weight: 700; cursor: pointer; }
        .error { background: #fdecea; color: #b42318; padding: 12px; border-radius: 10px; margin-bottom: 14px; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Connexion client</h1>
        <p>Entrez votre numero et votre mot de passe pour continuer.</p>

        <?php if (! empty($error)) : ?>
            <div class="error"><?= esc($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/client/login">
            <label for="num">Numero</label>
            <input id="num" name="num" type="text" value="<?= esc(old('num', $defaultNum ?? '')) ?>" required>

            <label for="mdp">Mot de passe</label>
            <input id="mdp" name="mdp" type="password" value="<?= esc(old('mdp', $defaultPassword ?? '')) ?>" required>

            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>