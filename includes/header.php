<?php $user = currentUser(); $flash = getFlash(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?> — <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(APP_URL) ?>/assets/css/style.css">
</head>
<body>
<?php if ($user): ?>
<header class="header">
    <div class="header-inner">
        <a href="<?= e(APP_URL) ?>/pages/dashboard.php" class="logo"><?= e(APP_NAME) ?></a>
        <nav class="nav">
            <?php if ($user['role'] === 'administrateur'): ?>
            <a href="<?= e(APP_URL) ?>/pages/admin/salles/index.php" class="nav-link">Salles</a>
            <?php endif; ?>
            <span class="user-info"><?= e($user['prenom'].' '.$user['nom']) ?> <small>(<?= e(roleLabel($user['role'])) ?>)</small></span>
            <a href="<?= e(APP_URL) ?>/logout.php" class="btn btn-outline btn-sm">Déconnexion</a>
        </nav>
    </div>
</header>
<?php endif; ?>
<main class="main">
<?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
