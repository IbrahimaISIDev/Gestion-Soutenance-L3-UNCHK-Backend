<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
require_once __DIR__ . '/../../../includes/models/Salle.php';
requireRole(['administrateur']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf($_POST['csrf_token'] ?? '')) {
    flash('error', 'Requête invalide.');
    redirect('/pages/admin/salles/index.php');
}

$result = Salle::delete((int)($_POST['id'] ?? 0));
flash($result['success'] ? 'success' : 'error', $result['message']);
redirect('/pages/admin/salles/index.php');
