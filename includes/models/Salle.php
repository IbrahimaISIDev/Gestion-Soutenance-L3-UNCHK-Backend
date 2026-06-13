<?php

class Salle
{
    public static function all(?string $search = null): array
    {
        $db = getDB();
        $sql = 'SELECT * FROM salles WHERE 1=1';
        $params = [];

        if ($search) {
            $sql .= ' AND (nom LIKE ? OR localisation LIKE ? OR equipements LIKE ?)';
            $term = '%' . $search . '%';
            $params = [$term, $term, $term];
        }

        $sql .= ' ORDER BY nom ASC';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM salles WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO salles (nom, capacite, localisation, equipements, actif) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$data['nom'], $data['capacite'], $data['localisation'] ?: null, $data['equipements'] ?: null, $data['actif'] ?? 1]);
        $id = (int) $db->lastInsertId();
        auditLog('creation', 'salle', $id, 'Salle : ' . $data['nom']);
        return $id;
    }

    public static function update(int $id, array $data): bool
    {
        $db = getDB();
        $stmt = $db->prepare('UPDATE salles SET nom=?, capacite=?, localisation=?, equipements=?, actif=? WHERE id=?');
        $ok = $stmt->execute([$data['nom'], $data['capacite'], $data['localisation'] ?: null, $data['equipements'] ?: null, $data['actif'] ?? 1, $id]);
        if ($ok) auditLog('modification', 'salle', $id, 'Salle : ' . $data['nom']);
        return $ok;
    }

    public static function delete(int $id): array
    {
        $salle = self::find($id);
        if (!$salle) return ['success' => false, 'message' => 'Salle introuvable.'];

        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM soutenances WHERE salle_id=? AND statut NOT IN ('annulee','realisee')");
        $stmt->execute([$id]);
        if ((int) $stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Impossible : des soutenances sont planifiées dans cette salle.'];
        }

        $db->prepare('DELETE FROM salles WHERE id=?')->execute([$id]);
        auditLog('suppression', 'salle', $id, 'Salle : ' . $salle['nom']);
        return ['success' => true, 'message' => 'Salle supprimée avec succès.'];
    }

    public static function validate(array $data, ?int $excludeId = null): array
    {
        $errors = [];
        if (trim($data['nom'] ?? '') === '') $errors['nom'] = 'Le nom est obligatoire.';
        if (!is_numeric($data['capacite'] ?? '') || (int) $data['capacite'] < 1) $errors['capacite'] = 'Capacité invalide (min 1).';

        $db = getDB();
        $sql = 'SELECT id FROM salles WHERE nom=?';
        $params = [trim($data['nom'] ?? '')];
        if ($excludeId) { $sql .= ' AND id!=?'; $params[] = $excludeId; }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch() && trim($data['nom'] ?? '') !== '') $errors['nom'] = 'Ce nom existe déjà.';

        return $errors;
    }
}
