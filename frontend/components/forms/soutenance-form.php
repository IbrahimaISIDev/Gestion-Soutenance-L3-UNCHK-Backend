<form method="POST" action="<?= $action ?? '/secretaire/soutenances/creer' ?>" class="soutenance-form" data-validate-form>
    <input type="hidden" name="id" value="<?= $id ?? '' ?>">
    
    <div class="form-group">
        <label for="titre" class="required">Titre</label>
        <input type="text" id="titre" name="titre" class="form-control" 
               value="<?= htmlspecialchars($titre ?? '') ?>" 
               data-validate="required|min:3" required>
        <div class="form-error" id="titre_error"></div>
    </div>
    
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($description ?? '') ?></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="date_heure" class="required">Date et heure</label>
            <input type="datetime-local" id="date_heure" name="date_heure" class="form-control" 
                   value="<?= $date_heure ?? '' ?>" data-validate="required" required>
            <div class="form-error" id="date_heure_error"></div>
        </div>
        
        <div class="form-group">
            <label for="duree" class="required">Durée (minutes)</label>
            <select id="duree" name="duree" class="form-control" data-validate="required" required>
                <option value="15" <?= ($duree ?? 30) == 15 ? 'selected' : '' ?>>15 minutes</option>
                <option value="20" <?= ($duree ?? 30) == 20 ? 'selected' : '' ?>>20 minutes</option>
                <option value="30" <?= ($duree ?? 30) == 30 ? 'selected' : '' ?>>30 minutes</option>
                <option value="45" <?= ($duree ?? 30) == 45 ? 'selected' : '' ?>>45 minutes</option>
                <option value="60" <?= ($duree ?? 30) == 60 ? 'selected' : '' ?>>60 minutes</option>
            </select>
            <div class="form-error" id="duree_error"></div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="etudiant_id" class="required">Étudiant</label>
            <select id="etudiant_id" name="etudiant_id" class="form-control" data-validate="required" required>
                <option value="">Choisir un étudiant</option>
                <?php foreach ($etudiants as $etudiant): ?>
                <option value="<?= $etudiant['id'] ?>" <?= ($etudiant_id ?? '') == $etudiant['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom'] . ' (' . $etudiant['matricule'] . ')') ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="form-error" id="etudiant_id_error"></div>
        </div>
        
        <div class="form-group">
            <label for="salle_id" class="required">Salle</label>
            <select id="salle_id" name="salle_id" class="form-control" data-validate="required" required>
                <option value="">Choisir une salle</option>
                <?php foreach ($salles as $salle): ?>
                <option value="<?= $salle['id'] ?>" <?= ($salle_id ?? '') == $salle['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($salle['nom'] . ' - Capacité: ' . $salle['capacite']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="form-error" id="salle_id_error"></div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="filiere" class="required">Filière</label>
            <select id="filiere" name="filiere" class="form-control" data-validate="required" required>
                <option value="">Choisir une filière</option>
                <?php foreach (['Informatique', 'Mathematiques', 'Physique', 'Chimie', 'Biologie'] as $filiere): ?>
                <option value="<?= $filiere ?>" <?= ($filiere_choisie ?? '') == $filiere ? 'selected' : '' ?>>
                    <?= $filiere ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="form-error" id="filiere_error"></div>
        </div>
        
        <div class="form-group">
            <label for="annee_academique" class="required">Année académique</label>
            <select id="annee_academique" name="annee_academique" class="form-control" data-validate="required" required>
                <option value="">Choisir une année</option>
                <?php foreach (['2022-2023', '2023-2024', '2024-2025'] as $annee): ?>
                <option value="<?= $annee ?>" <?= ($annee_academique ?? '') == $annee ? 'selected' : '' ?>>
                    <?= $annee ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="form-error" id="annee_academique_error"></div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <a href="/secretaire/soutenances" class="btn btn-secondary">
            <i class="fas fa-times"></i> Annuler
        </a>
    </div>
</form>