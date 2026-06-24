<div class="user-card">
    <div class="user-avatar">
        <i class="fas fa-user-circle"></i>
    </div>
    <div class="user-info">
        <h4><?= htmlspecialchars($nom ?? '') ?> <?= htmlspecialchars($prenom ?? '') ?></h4>
        <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($email ?? '') ?></p>
        <p><i class="fas fa-tag"></i> <span class="badge badge-<?= $role ?? '' ?>"><?= getRoleLabel($role ?? '') ?></span></p>
        <p><i class="fas fa-circle"></i> <span class="badge badge-<?= $statut ?? 'actif' ?>"><?= getStatusLabel($statut ?? 'actif') ?></span></p>
    </div>
    <div class="user-actions">
        <a href="/admin/utilisateurs/modifier/<?= $id ?? 0 ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i>
        </a>
        <button onclick="deleteUser(<?= $id ?? 0 ?>)" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>

<style>
.user-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: var(--transition);
}

.user-card:hover {
    box-shadow: var(--shadow-hover);
}

.user-avatar {
    font-size: 48px;
    color: var(--primary-color);
    flex-shrink: 0;
}

.user-info {
    flex: 1;
}

.user-info h4 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: var(--text-color);
}

.user-info p {
    margin: 3px 0;
    font-size: 13px;
    color: var(--text-light);
}

.user-info p i {
    width: 20px;
    color: var(--primary-color);
}

.user-actions {
    display: flex;
    gap: 8px;
}

@media (max-width: 480px) {
    .user-card {
        flex-direction: column;
        text-align: center;
    }
    
    .user-actions {
        width: 100%;
        justify-content: center;
    }
}
</style>