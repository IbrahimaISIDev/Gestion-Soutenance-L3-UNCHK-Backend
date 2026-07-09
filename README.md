# GestSoutenance — API Backend

> Système de Gestion des Soutenances Universitaires  
> Université Numérique Cheikh Hamidou Kane (UNCHK) — Projet Licence 3, Groupe S6

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel)](https://laravel.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-336791?logo=postgresql)](https://postgresql.org)
[![Docker](https://img.shields.io/badge/Docker-Alpine-2496ED?logo=docker)](https://docker.com)
[![Render](https://img.shields.io/badge/Render-Live-46E3B7?logo=render)](https://gest-soutenance-api.onrender.com)

---

## Table des matières

1. [Présentation du projet](#1-présentation-du-projet)
2. [Architecture générale](#2-architecture-générale)
3. [Stack technique](#3-stack-technique)
4. [Modèle de données](#4-modèle-de-données)
5. [Contrôle d'accès basé sur les rôles (RBAC)](#5-contrôle-daccès-basé-sur-les-rôles-rbac)
6. [Documentation de l'API REST](#6-documentation-de-lapi-rest)
7. [Services applicatifs](#7-services-applicatifs)
8. [Déploiement (Render + Docker)](#8-déploiement-render--docker)
9. [Installation locale](#9-installation-locale)
10. [Variables d'environnement](#10-variables-denvironnement)
11. [Comptes de test](#11-comptes-de-test)

---

## 1. Présentation du projet

GestSoutenance est une plateforme institutionnelle développée dans le cadre du projet de fin de formation en Licence 3 à l'UNCHK. Elle automatise et centralise l'ensemble du cycle de vie d'une soutenance universitaire : planification, composition des jurys, rédaction des procès-verbaux, validation pédagogique, génération de documents officiels et traçabilité des actions.

### Problématique

La gestion manuelle des soutenances (fichiers tableur, courriels épars, procès-verbaux papier) engendre des risques d'erreurs, des pertes d'information et un manque de traçabilité. Ce système apporte une solution numérique sécurisée, multi-rôles et auditable.

### Périmètre fonctionnel

| Domaine | Fonctionnalités |
|---|---|
| Authentification | Connexion email/mot de passe, tokens Bearer (Sanctum), déconnexion |
| Gestion des soutenances | Planification, confirmation, annulation, suivi de statut |
| Composition des jurys | Invitation des enseignants, confirmation ou refus de participation |
| Procès-verbaux | Saisie des notes, calcul automatique des mentions, soumission |
| Validation pédagogique | Approbation ou rejet d'un PV par le responsable pédagogique |
| Documents | Génération PDF, téléchargement sécurisé, hash d'intégrité SHA-256 |
| Notifications | Notifications in-app et emails transactionnels |
| Indisponibilités | Déclaration de créneaux bloquants par les enseignants |
| Administration | Gestion des utilisateurs, des salles, export des données, journal d'audit |

---

## 2. Architecture générale

```
┌──────────────────────────────────────────────────────────────┐
│                   CLIENT (React / Vercel)                     │
└──────────────────────┬───────────────────────────────────────┘
                       │  HTTPS — Authorization: Bearer <token>
                       ▼
┌──────────────────────────────────────────────────────────────┐
│            API REST Laravel 12  (Render / Docker)             │
│                                                              │
│  routes/api.php                                              │
│       │                                                      │
│       ▼                                                      │
│  Middleware Stack                                            │
│  ├── HandleCors      (origines via FRONTEND_URL)             │
│  ├── auth:sanctum    (validation du Bearer token)            │
│  └── CheckRole       (vérification du rôle utilisateur)      │
│       │                                                      │
│       ▼                                                      │
│  Controllers (organisés par rôle)                            │
│  ├── Api/AuthController                                      │
│  ├── Api/Admin/{User,Salle,Audit}Controller                  │
│  ├── Api/Secretaire/{Soutenance,Jury,Pv}Controller           │
│  ├── Api/Enseignant/{Soutenance,Jury,Indisponibilite}Ctrl    │
│  ├── Api/Responsable/{Pv,Export}Controller                   │
│  └── Api/{Document,Notification}Controller                   │
│       │                                                      │
│       ├──▶ Services (PdfService, MailService,                │
│       │              NotificationService, AuditService,      │
│       │              ExportService)                          │
│       │                                                      │
│       └──▶ Modèles Eloquent ──▶ PostgreSQL 16                │
│                                 (Render Managed DB)          │
└──────────────────────────────────────────────────────────────┘
```

Le backend suit le pattern **MVC** de Laravel enrichi d'une couche **Service** pour la logique métier transverse. Tous les échanges JSON sont normalisés via des **API Resources**. La sécurité repose sur deux middleware en cascade : `auth:sanctum` (token valide) puis `CheckRole` (rôle autorisé).

---

## 3. Stack technique

| Composant | Technologie | Version |
|---|---|---|
| Langage | PHP | 8.2 |
| Framework | Laravel | 12.x |
| Authentification API | Laravel Sanctum | 4.3 |
| Base de données | PostgreSQL | 16 |
| ORM | Eloquent | inclus Laravel |
| Conteneurisation | Docker (php:8.2-cli-alpine) | — |
| Hébergement API | Render — Web Service | Free |
| Hébergement BDD | Render — PostgreSQL | Free |
| Serveur HTTP (production) | PHP Built-in Server | port 10000 |

### Dépendances Composer (production uniquement)

```json
{
  "laravel/framework": "^12.0",
  "laravel/sanctum":   "^4.3",
  "laravel/tinker":    "^3.0"
}
```

---

## 4. Modèle de données

### Entités et attributs

```
users
├── id, name, email, password (bcrypt)
├── role  ← administrateur | secretaire_pedagogique | enseignant
│                             responsable_pedagogique | etudiant
├── department (nullable), phone (nullable)
├── is_active (boolean, défaut true)
└── email_verified_at

salles
├── id, nom (unique), capacite (integer)
├── localisation (nullable), equipements (text nullable)
└── actif (boolean, défaut true)

soutenances
├── id, titre, filiere
├── type  ← licence | master | doctorat
├── date (nullable), heure (nullable)
├── statut  ← brouillon | planifiee | confirmee | realisee | annulee
├── etudiant_id  → users
├── directeur_id → users
└── salle_id     → salles (nullable)

jury_membres
├── id, soutenance_id → soutenances
├── utilisateur_id    → users
├── role  ← president | directeur | rapporteur | membre
└── statut_confirmation  ← en_attente | confirme | refuse

pvs
├── id, soutenance_id → soutenances (unique)
├── note (decimal 4,2 nullable), mention (calculée auto)
├── observations (text nullable)
├── status  ← brouillon | en_validation | valide | signe | archive
└── fichier_pdf (chemin), signe_le (date nullable)

documents
├── id, soutenance_id → soutenances
├── type  ← pv | convocation | attestation
└── chemin_fichier, hash_fichier (SHA-256)

notifications
├── id, utilisateur_id → users
├── type, titre, message (text)
└── lu (boolean), lu_le (timestamp nullable)

indisponibilites
├── id, utilisateur_id → users
├── date_debut, date_fin, motif (nullable)

audit_logs
├── id, utilisateur_id → users (nullable)
├── action, details (text nullable)
└── ip_address, user_agent
```

### Calcul automatique des mentions

| Note obtenue | Mention attribuée |
|:---:|---|
| ≥ 16 / 20 | Très Bien |
| ≥ 14 / 20 | Bien |
| ≥ 12 / 20 | Assez Bien |
| ≥ 10 / 20 | Passable |
| < 10 / 20 | Ajourné |

### Cycle de vie d'une soutenance

```
brouillon ──▶ planifiee ──▶ confirmee ──▶ realisee
                  │               │
                  └───────────────▶ annulee
```

### Cycle de vie d'un PV

```
brouillon ──▶ en_validation ──▶ valide ──▶ signe ──▶ archive
                    │
                    └──▶ brouillon  (rejeté par le responsable)
```

---

## 5. Contrôle d'accès basé sur les rôles (RBAC)

### Implémentation

Le middleware `CheckRole` est enregistré sous l'alias `role:` et accepte un ou plusieurs rôles en paramètre. Il retourne HTTP 403 si l'utilisateur n'appartient à aucun des rôles autorisés.

```php
// app/Http/Middleware/CheckRole.php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (! Auth::check()) {
        return response()->json(['message' => 'Non authentifié.'], 401);
    }
    if (! in_array(Auth::user()->role, $roles, true)) {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }
    return $next($request);
}
```

### Matrice des droits

| Action | Admin | Secrétaire | Enseignant | Responsable | Étudiant |
|---|:---:|:---:|:---:|:---:|:---:|
| Gérer les utilisateurs | ✅ | — | — | — | — |
| Gérer les salles | ✅ | — | — | — | — |
| Journal d'audit | ✅ | — | — | — | — |
| Planifier / modifier soutenance | ✅ | ✅ | — | — | — |
| Confirmer / annuler soutenance | ✅ | ✅ | — | — | — |
| Gérer composition du jury | ✅ | ✅ | — | — | — |
| Saisir / soumettre un PV | ✅ | ✅ | — | — | — |
| Générer PDF du PV | ✅ | ✅ | — | — | — |
| Confirmer / refuser jury | — | — | ✅ | — | — |
| Déclarer indisponibilités | — | — | ✅ | — | — |
| Valider / rejeter un PV | — | — | — | ✅ | — |
| Exporter les données | — | — | — | ✅ | — |
| Consulter ses soutenances | — | — | — | — | ✅ |
| Notifications in-app | ✅ | ✅ | ✅ | ✅ | ✅ |
| Télécharger documents | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 6. Documentation de l'API REST

**URL de base :** `https://gest-soutenance-api.onrender.com/api`

Headers requis pour toutes les requêtes protégées :
```
Authorization: Bearer <token>
Content-Type: application/json
Accept: application/json
```

### 6.1 Authentification (public)

| Méthode | Endpoint | Description |
|---|---|---|
| `POST` | `/login` | Connexion — retourne `token` + `user` |
| `GET` | `/me` | Profil de l'utilisateur connecté |
| `POST` | `/logout` | Révocation du token courant |

**Exemple :**
```bash
curl -X POST https://gest-soutenance-api.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@gestsoutenance.test","password":"password"}'
```

**Réponse :**
```json
{
  "token": "1|enxKK4T6w1mQ30Q1j8BMrZpSC...",
  "user": {
    "id": 1, "name": "Aminata Diop",
    "email": "admin@gestsoutenance.test",
    "role": "administrateur", "is_active": true
  }
}
```

### 6.2 Administration — `/api/admin/*`

| Méthode | Endpoint | Description |
|---|---|---|
| `GET/POST` | `/admin/users` | Lister / créer des utilisateurs |
| `GET/PUT/DELETE` | `/admin/users/{id}` | Consulter / modifier / supprimer |
| `GET/POST` | `/admin/salles` | Lister / créer des salles |
| `GET/PUT/DELETE` | `/admin/salles/{id}` | Consulter / modifier / supprimer |
| `GET` | `/admin/audit` | Journal d'audit |
| `DELETE` | `/admin/audit/clean` | Purger les entrées anciennes |

### 6.3 Secrétariat — `/api/secretaire/*`

| Méthode | Endpoint | Description |
|---|---|---|
| `GET/POST` | `/secretaire/soutenances` | Lister / créer des soutenances |
| `GET/PUT/DELETE` | `/secretaire/soutenances/{id}` | Consulter / modifier / supprimer |
| `PUT` | `/secretaire/soutenances/{id}/confirm` | Confirmer |
| `PUT` | `/secretaire/soutenances/{id}/cancel` | Annuler |
| `POST` | `/secretaire/soutenances/{id}/jury` | Ajouter un membre au jury |
| `DELETE` | `/secretaire/jury/{id}` | Retirer un membre |
| `POST` | `/secretaire/soutenances/{id}/pv` | Créer le PV |
| `PUT` | `/secretaire/pv/{id}` | Mettre à jour le PV |
| `PUT` | `/secretaire/pv/{id}/submit` | Soumettre pour validation |
| `GET` | `/secretaire/pv/{id}/pdf` | Télécharger le PDF |

### 6.4 Enseignant — `/api/enseignant/*`

| Méthode | Endpoint | Description |
|---|---|---|
| `GET` | `/enseignant/soutenances` | Ses soutenances (directeur / jury) |
| `GET` | `/enseignant/jury` | Invitations jury reçues |
| `PUT` | `/enseignant/jury/{id}/confirm` | Confirmer la participation |
| `PUT` | `/enseignant/jury/{id}/decline` | Refuser la participation |
| `GET/POST` | `/enseignant/indisponibilites` | Lister / déclarer |
| `PUT/DELETE` | `/enseignant/indisponibilites/{id}` | Modifier / supprimer |

### 6.5 Responsable pédagogique — `/api/responsable/*`

| Méthode | Endpoint | Description |
|---|---|---|
| `GET` | `/responsable/pv` | PV soumis en attente |
| `PUT` | `/responsable/pv/{id}/validate` | Valider le PV |
| `PUT` | `/responsable/pv/{id}/reject` | Rejeter (commentaire requis) |
| `GET` | `/responsable/export/{format}` | Export csv / excel / pdf |

### 6.6 Étudiant — `/api/etudiant/*`

| Méthode | Endpoint | Description |
|---|---|---|
| `GET` | `/etudiant/soutenances` | Ses soutenances et leur statut |

### 6.7 Transversal (tous rôles)

| Méthode | Endpoint | Description |
|---|---|---|
| `GET` | `/notifications` | Ses notifications |
| `PUT` | `/notifications/{id}/read` | Marquer comme lue |
| `GET` | `/documents/{id}/download` | Télécharger un document |

---

## 7. Services applicatifs

| Service | Responsabilité |
|---|---|
| `AuditService` | Enregistre chaque action sensible dans `audit_logs` avec IP et user-agent |
| `MailService` | Envoi d'emails : invitations jury, convocations, notification de résultats |
| `NotificationService` | Point d'entrée unique : crée la notification in-app ET déclenche le mail |
| `PdfService` | Génère les documents PDF officiels (PV, convocations, attestations) |
| `ExportService` | Exporte les données en CSV, Excel ou PDF pour le responsable |

---

## 8. Déploiement (Render + Docker)

### Flux de déploiement

```
git push origin main
       │
       ▼  (webhook GitHub → Render)
Render Blueprint (render.yaml)
  ├── Web Service → Build Dockerfile → php -S :10000
  └── PostgreSQL  → gest_soutenance (provisionné une fois)
```

### Dockerfile

```dockerfile
FROM php:8.2-cli-alpine
RUN apk add --no-cache libpq-dev libzip-dev oniguruma-dev libxml2-dev zip unzip git
RUN docker-php-ext-install pdo_pgsql pdo_mysql mbstring bcmath zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction
COPY . .
RUN composer dump-autoload --optimize \
    && mkdir -p bootstrap/cache storage/framework/{cache/data,sessions,views} storage/logs \
    && chmod -R 777 bootstrap/cache storage
EXPOSE 10000
CMD sh -c "php artisan config:cache && php artisan route:clear \
    && php artisan migrate --force && php artisan db:seed --force \
    && php -S 0.0.0.0:${PORT:-10000} -t public"
```

**Points techniques :**
- Image Alpine minimale (~80 Mo), réduction de la surface d'attaque
- `.dockerignore` exclut `vendor/`, `client/`, `tests/`, `.env` pour éviter toute corruption du vendor installé par Composer
- `--no-scripts` + `dump-autoload` séparé : optimise le cache des layers Docker
- Seeders idempotents via `firstOrCreate()` : le `db:seed` au démarrage n'écrase jamais les données existantes

### Variables Render à configurer manuellement

| Variable | Valeur |
|---|---|
| `APP_KEY` | `base64:` + 32 octets encodés en base64 |
| `APP_URL` | `https://gest-soutenance-api.onrender.com` |
| `FRONTEND_URL` | URL Vercel du frontend (critique pour CORS) |

---

## 9. Installation locale

### Prérequis

- PHP ≥ 8.2 avec extensions `pdo_pgsql`, `mbstring`, `bcmath`, `zip`
- Composer 2.x
- PostgreSQL 14+

### Étapes

```bash
git clone https://github.com/IbrahimaISIDev/Gestion-Soutenance-L3-UNCHK-Backend.git
cd Gestion-Soutenance-L3-UNCHK-Backend
composer install
cp .env.example .env
# Éditer .env : DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, FRONTEND_URL
php artisan key:generate
php artisan migrate --seed
php artisan serve
# API disponible sur http://localhost:8000/api
```

---

## 10. Variables d'environnement

```dotenv
APP_NAME=GestSoutenance
APP_ENV=local
APP_KEY=           # php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gest_soutenance
DB_USERNAME=gest_admin
DB_PASSWORD=

# Origine CORS autorisée (doit correspondre exactement à l'URL du frontend)
FRONTEND_URL=http://localhost:5173

SESSION_DRIVER=array
CACHE_STORE=array
QUEUE_CONNECTION=sync
MAIL_MAILER=log
LOG_CHANNEL=stderr
LOG_LEVEL=debug
```

---

## 11. Comptes de test

Mot de passe universel : **`password`**

| Rôle | Nom | Email |
|---|---|---|
| Administrateur | Aminata Diop | `admin@gestsoutenance.test` |
| Secrétaire pédagogique | Fatou Ndiaye | `secretaire@gestsoutenance.test` |
| Responsable pédagogique | Moussa Sarr | `responsable@gestsoutenance.test` |
| Enseignant | Ibrahima Fall | `ibrahima-fall@gestsoutenance.test` |
| Enseignant | Awa Camara | `awa-camara@gestsoutenance.test` |
| Enseignant | Cheikh Diallo | `cheikh-diallo@gestsoutenance.test` |
| Enseignant | Mariama Ba | `mariama-ba@gestsoutenance.test` |
| Enseignant | Ousmane Gueye | `ousmane-gueye@gestsoutenance.test` |
| Étudiant | Mamadou Diao | `mamadou-diao@etudiant.gestsoutenance.test` |
| Étudiant | Khady Sow | `khady-sow@etudiant.gestsoutenance.test` |
| Étudiant | Babacar Toure | `babacar-toure@etudiant.gestsoutenance.test` |
| Étudiant | Aissatou Barry | `aissatou-barry@etudiant.gestsoutenance.test` |
| Étudiant | Modou Lo | `modou-lo@etudiant.gestsoutenance.test` |
| Étudiant | Bineta Diatta | `bineta-diatta@etudiant.gestsoutenance.test` |

---

## Équipe

Projet réalisé dans le cadre de la **Licence 3 — Informatique**  
**Université Numérique Cheikh Hamidou Kane (UNCHK)** — Groupe S6

*API en production : [https://gest-soutenance-api.onrender.com](https://gest-soutenance-api.onrender.com)*
