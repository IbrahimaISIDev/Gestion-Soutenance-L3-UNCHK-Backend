<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Étudiant</th>
                    <th>Date</th>
                    <th>Salle</th>
                    <th>Filière</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($soutenances)): ?>
                <tr>
                    <td colspan="8" class="empty-state">
                        <i class="fas fa-calendar-plus"></i>
                        <h4>Aucune soutenance</h4>
                        <p>Créez une nouvelle soutenance en cliquant sur le bouton ci-dessus.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($soutenances as $s): ?>
                <tr>
                    <td>#<?= $s['id'] ?></td>
                    <td><?= htmlspecialchars($s['titre']) ?></td>
                    <td><?= htmlspecialchars($s['etudiant_nom'] ?? '') ?> <?= htmlspecialchars($s['etudiant_prenom'] ?? '') ?></td>
                    <td><?= formatDate($s['date_heure']) ?></td>
                    <td><?= htmlspecialchars($s['salle_nom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($s['filiere']) ?></td>
                    <td><span class="badge badge-<?= $s['statut'] ?>"><?= getStatusLabel($s['statut']) ?></span></td>
                    <td>
                        <a href="/secretaire/soutenances/voir/<?= $s['id'] ?>" class="btn btn-sm btn-primary" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if ($s['statut'] === 'planifiee'): ?>
                        <a href="/secretaire/soutenances/modifier/<?= $s['id'] ?>" class="btn btn-sm btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteSoutenance(<?= $s['id'] ?>)" class="btn btn-sm btn-danger" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>