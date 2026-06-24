<div class="soutenance-card">
    <div class="soutenance-header">
        <h4><?= htmlspecialchars($titre ?? '') ?></h4>
        <span class="badge badge-<?= $statut ?? 'planifiee' ?>"><?= getStatusLabel($statut ?? 'planifiee') ?></span>
    </div>
    <div class="soutenance-body">
        <p><i class="fas fa-user"></i> <?= htmlspecialchars($etudiant ?? '') ?></p>
        <p><i class="fas fa-calendar"></i> <?= formatDate($date ?? '') ?></p>
        <p><i class="fas fa-clock"></i> <?= $duree ?? 0 ?> minutes</p>
        <p><i class="fas fa-door-open"></i> <?= htmlspecialchars($salle ?? '') ?></p>
    </div>
    <div class="soutenance-footer">
        <a href="/soutenances/voir/<?= $id ?? 0 ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-eye"></i> Voir
        </a>
        <?php if ($statut === 'planifiee'): ?>
        <a href="/soutenances/modifier/<?= $id ?? 0 ?>" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <?php endif; ?>
    </div>
</div>

<style>
.soutenance-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.soutenance-card:hover {
    box-shadow: var(--shadow-hover);
}

.soutenance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.soutenance-header h4 {
    margin: 0;
    font-size: 16px;
    color: var(--text-color);
}

.soutenance-body p {
    margin: 8px 0;
    color: var(--text-light);
    font-size: 14px;
}

.soutenance-body p i {
    width: 20px;
    color: var(--primary-color);
}

.soutenance-footer {
    display: flex;
    gap: 8px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
</style>