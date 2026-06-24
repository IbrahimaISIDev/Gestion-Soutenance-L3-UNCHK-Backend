<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Capacité</th>
                    <th>Bâtiment</th>
                    <th>Étage</th>
                    <th>Équipements</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($salles)): ?>
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-door-open"></i>
                        <h4>Aucune salle</h4>
                        <p>Aucune salle enregistrée pour le moment.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($salles as $salle): ?>
                <tr>
                    <td><?= htmlspecialchars($salle['nom']) ?></td>
                    <td><?= $salle['capacite'] ?> places</td>
                    <td><?= htmlspecialchars($salle['batiment'] ?? '-') ?></td>
                    <td><?= $salle['etage'] ?? '-' ?></td>
                    <td><?= htmlspecialchars($salle['equipements'] ?? '-') ?></td>
                    <td>
                        <?php if ($salle['disponible']): ?>
                        <span class="badge badge-success">Disponible</span>
                        <?php else: ?>
                        <span class="badge badge-danger">Indisponible</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="editSalle(<?= $salle['id'] ?>)" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteSalle(<?= $salle['id'] ?>)" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>