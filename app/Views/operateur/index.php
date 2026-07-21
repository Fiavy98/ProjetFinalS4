<?php $currentPage = 'dashboard'; $pageTitle = 'Tableau de bord'; require __DIR__ . '/_header.php'; ?>

<section class="yas-hero">
    <span class="yas-kicker">YAS · Administration</span>
    <h1 class="yas-title">Espace opérateur YAS</h1>
    <p>Bienvenue dans votre tableau de bord. Consultez les opérations, configurez les frais et suivez les gains de l’opérateur depuis un même espace.</p>
</section>

<section class="yas-grid" aria-label="Accès rapides">
    <article class="yas-card"><h2>Mon profil</h2><p>Identité de l’opérateur et préfixes téléphoniques pris en charge.</p><a href="/operateur/profil">Voir le profil →</a></article>
    <article class="yas-card"><h2>Opérations</h2><p>Consultez l’ensemble des transactions réalisées par les clients.</p><a href="/operateur/operations">Voir les opérations →</a></article>
    <article class="yas-card"><h2>Grille des frais</h2><p>Retrouvez les seuils et les frais appliqués à chaque opération.</p><a href="/operateur/frais">Gérer les frais →</a></article>
    <article class="yas-card"><h2>Historique des gains</h2><p>Suivez les gains enregistrés par type d’opération et par date.</p><a href="/operateur/gains">Voir les gains →</a></article>
    <article class="yas-card"><h2>Montants à envoyer</h2><p>Consultez les transferts externes à régler par opérateur.</p><a href="/operateur/montants-a-envoyer">Voir la situation →</a></article>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
