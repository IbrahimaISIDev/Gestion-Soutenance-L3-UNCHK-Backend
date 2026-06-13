<?php

function initSession(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['last_activity'] = time();
}

function isLoggedIn(): bool { return isset($_SESSION['user']); }
function currentUser(): ?array { return $_SESSION['user'] ?? null; }

function requireLogin(): void
{
    if (!isLoggedIn()) {
        flash('error', 'Veuillez vous connecter.');
        redirect('/login.php');
    }
}

function requireRole(array $roles): void
{
    requireLogin();
    if (!in_array($_SESSION['user']['role'] ?? '', $roles, true)) {
        flash('error', 'Accès non autorisé.');
        redirect('/pages/dashboard.php');
    }
}

function login(string $email, string $password): bool
{
    $db = getDB();
    $stmt = $db->prepare('SELECT id, nom, prenom, email, mot_de_passe, role, actif FROM utilisateurs WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !$user['actif'] || !password_verify($password, $user['mot_de_passe'])) return false;

    unset($user['mot_de_passe']);
    $_SESSION['user'] = $user;
    auditLog('connexion', 'utilisateur', (int) $user['id']);
    return true;
}

function logout(): void
{
    if (isLoggedIn()) auditLog('deconnexion', 'utilisateur', (int) $_SESSION['user']['id']);
    session_unset();
    session_destroy();
}

function dashboardForRole(string $role): string
{
    return ROLE_DASHBOARDS[$role] ?? '/pages/dashboard.php';
}
