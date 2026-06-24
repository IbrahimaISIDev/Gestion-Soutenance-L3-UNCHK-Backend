<form method="POST" action="<?= $action ?? '/enseignant/indisponibilites/ajouter' ?>" data-validate-form>
    <div class="form-group">
        <label for="date" class="required">Date</label>
        <input type="date" id="date" name="date" class="form-control" 
               data-validate="required" required>
        <div class="form-error" id="date_error"></div>
    </div>
    
    <div class="form-group">
        <label>Périodes</label>
        <div class="form-check">
            <input type="checkbox" id="matin" name="matin" value="1">
            <label for="matin">Matin (8h-12h)</label>
        </div>
        <div class="form-check">
            <input type="checkbox" id="apres_midi" name="apres_midi" value="1">
            <label for="apres_midi">Après-midi (14h-18h)</label>
        </div>
    </div>
    
    <div class="form-group">
        <label for="motif">Motif (optionnel)</label>
        <input type="text" id="motif" name="motif" class="form-control" 
               placeholder="Ex: Congé, Formation, etc.">
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <button type="button" class="btn btn-secondary" onclick="closeModal('indispoModal')">
            <i class="fas fa-times"></i> Annuler
        </button>
    </div>
</form>