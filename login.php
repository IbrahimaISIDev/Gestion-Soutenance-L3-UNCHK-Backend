<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (isLoggedIn()) redirect(dashboardForRole($_SESSION['user']['role']));

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') $error = 'Veuillez remplir tous les champs.';
    elseif (login($email, $password)) redirect(dashboardForRole($_SESSION['user']['role']));
    else $error = 'Email ou mot de passe incorrect.';
}

$pageTitle = 'Connexion';
require_once __DIR__ . '/includes/header.php';
?>
<div class="auth-container">
    <div class="auth-card">
        <h1><?= e(APP_NAME) ?></h1>
        <p class="subtitle">Système de Gestion des Soutenances Universitaires</p>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
        <form method="POST" class="form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
