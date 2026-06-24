<form method="POST" action="<?= $action ?? '' ?>" data-validate-form>
    <div class="form-group">
        <label for="note" class="required">Note sur 20</label>
        <input type="number" id="note" name="note" class="form-control" 
               step="0.5" min="0" max="20" 
               value="<?= $note ?? '' ?>"
               data-validate="required|min_value:0|max_value:20" required>
        <div class="form-error" id="note_error"></div>
        <small class="form-help">Note de 0 à 20, avec des demi-points possibles.</small>
    </div>
    
    <div class="form-group">
        <label for="mention">Mention (calculée automatiquement)</label>
        <input type="text" id="mention" name="mention" class="form-control" 
               value="<?= isset($mention) ? getMentionLabel($mention) : '' ?>" readonly disabled>
        <small class="form-help">La mention est calculée automatiquement en fonction de la note.</small>
    </div>
    
    <div class="form-group">
        <label for="commentaire">Commentaire</label>
        <textarea id="commentaire" name="commentaire" class="form-control" rows="4"><?= htmlspecialchars($commentaire ?? '') ?></textarea>
        <small class="form-help">Commentaires sur la prestation de l'étudiant.</small>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <?php if (($statut ?? 'brouillon') === 'brouillon'): ?>
        <button type="submit" name="action" value="soumettre" class="btn btn-success">
            <i class="fas fa-paper-plane"></i> Soumettre pour validation
        </button>
        <?php endif; ?>
        <a href="<?= $retour ?? '/enseignant/mes-soutenances' ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> Annuler
        </a>
    </div>
</form>

<script>
// Calcul automatique de la mention
document.addEventListener('DOMContentLoaded', function() {
    const noteInput = document.getElementById('note');
    const mentionInput = document.getElementById('mention');
    
    if (noteInput && mentionInput) {
        noteInput.addEventListener('input', function() {
            const note = parseFloat(this.value);
            
            if (isNaN(note)) {
                mentionInput.value = '';
                return;
            }
            
            let mention = '';
            if (note >= 18) mention = 'Excellent';
            else if (note >= 16) mention = 'Très Bien';
            else if (note >= 14) mention = 'Bien';
            else if (note >= 12) mention = 'Assez Bien';
            else if (note >= 10) mention = 'Passable';
            else mention = 'Insuffisant';
            
            mentionInput.value = mention;
        });
    }
});
</script>