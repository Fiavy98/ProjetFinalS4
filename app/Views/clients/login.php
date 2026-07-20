<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion client</title>
    <link rel="stylesheet" href="/assets/client.css">
</head>
<body class="client-page">
    <div class="login-layout page-shell">
        <section class="login-card">
            <div class="brand" style="margin-bottom: 18px;">
                <div class="brand-mark">MM</div>
                <div>
                    <div>Mobile Money</div>
                    <small class="muted">Connexion client</small>
                </div>
            </div>

            <h1>Accedez à votre compte</h1>
            <p class="muted">Entrez votre numero et votre mot de passe pour continuer.</p>

            <?php if (! empty($error)) : ?>
                <div class="alert alert-error"><?= esc($error) ?></div>
            <?php endif; ?>

            <form method="post" action="/client/login" class="form-grid">
                <div>
                    <label for="num">Numero</label>
                    <input id="num" name="num" type="text" value="<?= esc(old('num', $defaultNum ?? '')) ?>" placeholder="0341234567" required>
                </div>

                <div>
                    <label for="mdp">Mot de passe</label>
                    <input id="mdp" name="mdp" type="password" value="<?= esc(old('mdp', $defaultPassword ?? '')) ?>" placeholder="1234" required>
                </div>

                <button class="btn" type="submit">Se connecter</button>
            </form>

            <p class="footer-note">Developpeurs: ETU003993 · ETU004373</p>
        </section>
    </div>
</body>
</html>