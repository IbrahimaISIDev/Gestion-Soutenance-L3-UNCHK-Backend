<form method="POST" action="<?= $action ?? '/admin/utilisateurs/creer' ?>" class="user-form" data-validate-form>
    <input type="hidden" name="id" value="<?= $id ?? '' ?>">
    
    <div class="form-row">
        <div class="form-group">
            <label for="nom" class="required">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" 
                   value="<?= htmlspecialchars($nom ?? '') ?>" 
                   data-validate="required|min:2" required>
            <div class="form-error" id="nom_error"></div>
        </div>
        
        <div class="form-group">
            <label for="prenom" class="required">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control" 
                   value="<?= htmlspecialchars($prenom ?? '') ?>" 
                   data-validate="required|min:2" required>
            <div class="form-error" id="prenom_error"></div>
        </div>
    </div>
    
    <div class="form-group">
        <label for="email" class="required">Email</label>
        <input type="email" id="email" name="email" class="form-control" 
               value="<?= htmlspecialchars($email ?? '') ?>" 
               data-validate="required|email" required>
        <div class="form-error" id="email_error"></div>
    </div>
    
    <div class="form-group">
        <label for="password"><?= isset($id) ? 'Nouveau mot de passe' : 'Mot de passe' ?></label>
        <input type="password" id="password" name="password" class="form-control" 
               placeholder="<?= isset($id) ? 'Laissez vide pour ne pas modifier' : '••••••••' ?>"
               <?= isset($id) ? '' : 'data-validate="required|min:6" required' ?>>
        <div class="form-error" id="password_error"></div>
        <?php if (isset($id)): ?>
        <small class="form-help">Laissez vide pour ne pas modifier le mot de passe</small>
        <?php endif; ?>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="role" class="required">Rôle</label>
            <select id="role" name="role" class="form-control" data-validate="required" required>
                <option value="etudiant" <?= ($role ?? '') === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
                <option value="enseignant" <?= ($role ?? '') === 'enseignant' ? 'selected' : '' ?>>Enseignant</option>
                <option value="secretaire" <?= ($role ?? '') === 'secretaire' ? 'selected' : '' ?>>Secrétaire</option>
                <option value="responsable" <?= ($role ?? '') === 'responsable' ? 'selected' : '' ?>>Responsable</option>
                <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>
            <div class="form-error" id="role_error"></div>
        </div>
        
        <div class="form-group">
            <label for="statut">Statut</label>
            <select id="statut" name="statut" class="form-control">
                <option value="actif" <?= ($statut ?? 'actif') === 'actif' ? 'selected' : '' ?>>Actif</option>
                <option value="inactif" <?= ($statut ?? '') === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                <option value="suspendu" <?= ($statut ?? '') === 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label for="telephone">Téléphone</label>
        <input type="tel" id="telephone" name="telephone" class="form-control" 
               value="<?= htmlspecialchars($telephone ?? '') ?>" data-validate="phone">
        <div class="form-error" id="telephone_error"></div>
    </div>
    
    <div class="form-group">
        <label for="matricule">Matricule</label>
        <input type="text" id="matricule" name="matricule" class="form-control" 
               value="<?= htmlspecialchars($matricule ?? '') ?>">
    </div>
    
    <div class="form-group">
        <label for="specialite">Spécialité</label>
        <input type="text" id="specialite" name="specialite" class="form-control" 
               value="<?= htmlspecialchars($specialite ?? '') ?>">
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <a href="/admin/utilisateurs" class="btn btn-secondary">
            <i class="fas fa-times"></i> Annuler
        </a>
    </div>
</form>