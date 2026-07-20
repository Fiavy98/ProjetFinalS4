<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Accueil</title>
    <link rel="stylesheet" href="/assets/client.css">
</head>
<body class="client-page">
    <div class="page-shell">
        <?php
        $adminMessage = session()->getFlashdata('admin_message');
        $adminError = session()->getFlashdata('admin_error');
        ?>

        <header class="brandbar">
            <div class="brand">
                <div class="brand-mark">MM</div>
                <div>
                    <div>Mobile Money</div>
                    <small class="muted">Plateforme client et opérateur</small>
                </div>
            </div>
            <nav class="nav-links">
                <a class="chip" href="/client/login">Connexion client</a>
                <a class="chip" href="/operateur">Espace operateur</a>
            </nav>
        </header>

        <?php if (! empty($adminMessage)) : ?>
            <div class="alert alert-success"><?= esc($adminMessage) ?></div>
        <?php endif; ?>

        <?php if (! empty($adminError)) : ?>
            <div class="alert alert-error"><?= esc($adminError) ?></div>
        <?php endif; ?>

        <section class="hero-grid">
            <article class="panel hero">
                <span class="eyebrow">Projet CodeIgniter 4 + SQLite</span>
                <h1>Une expérience mobile money claire, rapide et moderne.</h1>
                <p class="lead">
                    Dépôt, retrait, transfert, solde et historique client dans une interface épurée,
                    pensée pour être simple à utiliser et facile à maintenir.
                </p>

                <div class="hero-actions">
                    <a class="btn" href="/client/login">Acceder au login client</a>
                    <a class="btn-secondary" href="/client/operations">Voir les operations</a>
                    <a class="btn-ghost" href="/operateur">Aller au dashboard operateur</a>
                </div>

                <div class="hero-stats">
                    <div class="stat">
                        <strong>4</strong>
                        <span>types d'operations prises en charge</span>
                    </div>
                    <div class="stat">
                        <strong>2</strong>
                        <span>espaces distincts client / operateur</span>
                    </div>
                    <div class="stat">
                        <strong>1</strong>
                        <span>base SQLite unique pour les tests</span>
                    </div>
                </div>
            </article>

            <aside class="panel side-card">
                <div class="mini-card">
                    <h3>Connexion admin</h3>
                    <p class="muted">Acces rapide a l'espace operateur via un identifiant fixe.</p>
                    <form method="post" action="/operateur/login" class="form-grid" style="margin-top: 14px;">
                        <div>
                            <label for="admin_num">Numero admin</label>
                            <input id="admin_num" name="num" type="text" value="999999999" required>
                        </div>
                        <div>
                            <label for="admin_mdp">Mot de passe</label>
                            <input id="admin_mdp" name="mdp" type="password" value="admin123" required>
                        </div>
                        <button class="btn" type="submit">Entrer dans l espace operateur</button>
                    </form>
                </div>
                <div class="mini-card">
                    <h3>Fonctions rapides</h3>
                    <p class="muted">Connexion par numero, gestion des comptes, et suivi des mouvements.</p>
                </div>
                <div class="mini-card">
                    <h3>Qualite de code</h3>
                    <p class="muted">Architecture MVC CodeIgniter 4, vues separées et routes propres.</p>
                </div>
                <div class="mini-card">
                    <h3>Developpeurs</h3>
                    <p class="muted">ETU003993 et ETU004373 ont conçu l’exercice et cette interface.</p>
                </div>
            </aside>
        </section>

        <section class="section">
            <div class="section-grid">
                <div class="feature-card">
                    <span class="developer-tag">Client</span>
                    <h3>Operations essentielles</h3>
                    <p class="muted">Depot, retrait et transfert avec un chemin d'utilisation simple et direct.</p>
                </div>
                <div class="feature-card">
                    <span class="developer-tag">Operateur</span>
                    <h3>Contrôle et frais</h3>
                    <p class="muted">Liste des opérations, frais, profil et situation des gains depuis l'espace operateur.</p>
                </div>
                <div class="feature-card">
                    <span class="developer-tag">Equipe</span>
                    <h3>ETU003993 / ETU004373</h3>
                    <p class="muted">Travail d'exercice pour une application mobile money propre et structurée.</p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>