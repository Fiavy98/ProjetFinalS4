<?php $currentPage = 'profil'; $pageTitle = 'Profil opérateur'; require __DIR__ . '/_header.php'; ?>
<section class="yas-page-heading"><span class="yas-kicker">YAS · Paramètres</span><h1>Profil opérateur</h1><p>Les informations associées à votre opérateur YAS.</p></section>
<section class="yas-card yas-profile">
    <div><span>Nom de l’opérateur</span><strong><?= esc($operateur['nom']) ?></strong></div>
    <div><span>Préfixes pris en charge</span><strong><?= esc($operateur['prefixes']) ?></strong></div>
</section>
<?php require __DIR__ . '/_footer.php'; ?>
