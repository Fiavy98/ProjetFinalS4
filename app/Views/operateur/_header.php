<?php
$currentPage = $currentPage ?? '';
$pageTitle = $pageTitle ?? 'Espace opérateur';
$pageDescription = $pageDescription ?? 'Pilotez les activités de votre opérateur.';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle) ?> | YAS</title>
    <link rel="stylesheet" href="/assets/operateur.css">
</head>
<body class="yas-page">
    <main class="yas-shell">
        <header class="yas-header">
            <a class="yas-brand" href="/operateur" aria-label="YAS — accueil opérateur">
                <img class="yas-logo" src="/assets/yas-logo.svg" alt="Logo YAS">
                <span class="yas-brand-copy"><span class="yas-brand-name">YAS</span><small>Espace opérateur</small></span>
            </a>
            <nav class="yas-nav" aria-label="Navigation opérateur">
                <a href="/operateur" <?= $currentPage === 'dashboard' ? 'aria-current="page"' : '' ?>>Accueil</a>
                <a href="/operateur/profil" <?= $currentPage === 'profil' ? 'aria-current="page"' : '' ?>>Profil</a>
                <a href="/operateur/operations" <?= $currentPage === 'operations' ? 'aria-current="page"' : '' ?>>Opérations</a>
                <a href="/operateur/frais" <?= $currentPage === 'frais' ? 'aria-current="page"' : '' ?>>Frais</a>
                <a href="/operateur/gains" <?= $currentPage === 'gains' ? 'aria-current="page"' : '' ?>>Gains</a>
            </nav>
        </header>
        <?php if (session()->getFlashdata('frais_success')): ?><p class="yas-alert yas-alert-success"><?= esc(session()->getFlashdata('frais_success')) ?></p><?php endif; ?>
        <?php if (session()->getFlashdata('frais_error')): ?><p class="yas-alert yas-alert-error"><?= esc(session()->getFlashdata('frais_error')) ?></p><?php endif; ?>
