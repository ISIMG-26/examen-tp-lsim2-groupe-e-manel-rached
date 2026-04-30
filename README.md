# 🚗 Covoiturage TN — Mini-Projet Web

---

## 👥 Membres du groupe

| **[Rached Manel ]** | Groupe [E] — LSIM 2 |


## 📋 Description du projet

**Covoiturage TN** est une application web dynamique de réservation de trajets partagés en Tunisie. Elle permet aux utilisateurs de :

- **Rechercher** des trajets disponibles entre villes tunisiennes
- **Réserver** une ou plusieurs places sur un trajet
- **Publier** leurs propres trajets pour partager les frais
- **Gérer** leurs trajets publiés et leurs réservations
- **Modifier** leur profil personnel

Le projet est de type **e-commerce / réservation en ligne**, thème : covoiturage.

---

## 📁 Structure du projet

```
/covoiturage-tn
├── index.html              ← Page d'accueil (recherche + liste des trajets)
├── login.html              ← Page de connexion
├── register.html           ← Page d'inscription
├── ride-detail.html        ← Détail d'un trajet + carte + réservation
├── myrides.html            ← Mes trajets publiés et réservés
├── publish.html            ← Publier un nouveau trajet
├── profile.html            ← Mon profil + modification
├── README.md
│
├── assets/
│   ├── css/
│   │   └── style.css       ← Feuille de style externe unique 
│   └── js/
│       └── app.js          ← JavaScript externe partagé (API, DOM, validation)
│
├── database/
│   └── script.sql          ← Script de création de la base de données
│
└── back/                   ← Fichiers PHP (dans covoiturage-api/)
    ├── config/
    │   ├── cors.php
    │   └── db.php
    ├── auth/
    │   ├── login.php
    │   ├── register.php
    │   ├── getProfile.php
    │   └── updateProfile.php
    └── rides/
        ├── getRides.php
        ├── post.php
        ├── bookRide.php
        ├── getAvailableSeats.php
        ├── getBookings.php
        ├── getUserRides.php
        └── deleteRide.php
```

---

## ⚙️ Installation et lancement (XAMPP)

### Étape 1 — Placer les fichiers
```
C:/xampp/htdocs/
├── covoiturage-tn/     ← ce dossier (frontend)
└── covoiturage-api/    ← backend PHP
```

### Étape 2 — Créer la base de données
1. Démarrer **Apache** et **MySQL** dans XAMPP
2. Ouvrir `http://localhost/phpmyadmin`
3. Créer une nouvelle base : `covoiturage_tn`
4. Onglet **Import** → Choisir `database/script.sql` → Exécuter

### Étape 3 — Lancer le site
Ouvrir : `http://localhost/covoiturage-tn/`

### Compte de test
```
Email    : ahmed@test.tn
Mot de passe : password
```

---

## 🗄️ Base de données

**Nom :** `covoiturage_tn` — **3 tables liées**

```
users ──< rides       (un utilisateur peut avoir plusieurs trajets)
users ──< bookings    (un utilisateur peut faire plusieurs réservations)
rides ──< bookings    (un trajet peut avoir plusieurs réservations)
```

| Table | Description |
|-------|-------------|
| `users` | Utilisateurs inscrits (id, nom, email, téléphone, mot de passe hashé) |
| `rides` | Trajets publiés (id, conducteur, villes, date, places, prix, statut) |
| `bookings` | Réservations (id, trajet, passager, places réservées, statut) |

---

## ✅ Checklist de conformité

### Partie 1 — Structure HTML
| Critère | Statut | Fichier |
|---------|--------|---------|
| ≥ 3 pages interconnectées |  7 pages HTML |
| Balises sémantiques `<header>` `<nav>` `<main>` `<section>` `<article>` `<aside>` `<footer>` |  Toutes les pages |
| Menu de navigation fonctionnel sur toutes les pages |  `buildNav()` dans `app.js` |

### Partie 2 — CSS
| Critère | Statut | Fichier |
|---------|--------|---------|
| Fichier CSS externe |  `assets/css/style.css` |
| Cohérence graphique |  Variables CSS, palette bleue |
| Mise en page structurée |  CSS Grid + Flexbox |

### Partie 3 — JavaScript DOM
| Critère | Statut | Preuve |
|---------|--------|--------|
| `getElementById` | | 91 utilisations dans les pages |
| `querySelector` / `querySelectorAll` |  `myrides.html`, `profile.html` |
| Modification dynamique du contenu | `innerHTML`, `textContent` |
| Ajout d'éléments |  `createElement` + `appendChild` dans `myrides.html` |
| Suppression d'éléments |  Filtre + suppression carte sans rechargement |
| Gestion des événements | `addEventListener` sur boutons, forms, modals |
| Fonctionnalité interactive visible |  Filtre/tri trajets, compteur de places, aperçu live |

### Partie 4 — Validation des formulaires
| Critère | Statut | Preuve |
|---------|--------|--------|
| Champs obligatoires vérifiés |  `Validators.required` |
| Format email validé |  `Validators.email` (regex) |
| Format téléphone validé | `Validators.phone` (8 chiffres) |
| Format mot de passe validé | `Validators.minLen(v, 6)` |
| Messages d'erreur affichés |  `.form-error` sous chaque champ |
| Blocage si données invalides |  `if (!valid) return;` |

### Partie 5 — AJAX
| Critère | Statut | Preuve |
|---------|--------|--------|
| Utilisation de `fetch` | `apiFetch()` dans `app.js` |
| Mise à jour sans rechargement |  Toutes les pages |
| Fonctionnalité pertinente |  Recherche, filtrage, réservation, suppression |

### Partie 6 — PHP
| Critère | Statut | Fichier |
|---------|--------|---------|
| `$_GET` |  `getRides.php`, `getProfile.php`, `getBookings.php` |
| `$_POST` / `php://input`  `login.php`, `register.php`, `post.php`, `bookRide.php` |
| Traitement complet des formulaires  Validation + requête SQL + réponse JSON |
| Organisation en scripts distincts  9 fichiers PHP séparés |
| Génération de contenu dynamique  JSON dynamique selon les données MySQL |

### Partie 7 — MySQL
| Opération | Fichier PHP | Déclencheur |
|-----------|-------------|-------------|
| **SELECT** | `getRides.php`, `getProfile.php`, `getBookings.php`, `getUserRides.php` | Chargement des pages |
| **INSERT** | `register.php`, `post.php`, `bookRide.php` | Inscription, publication, réservation |
| **UPDATE** | `bookRide.php`, `updateProfile.php` | Réservation (places), modification profil |
| **DELETE** | `deleteRide.php` | Bouton Supprimer dans "Mes Trajets" |

### Partie 8 — Qualité
| Critère | Statut |
|---------|--------|
| Textes lisibles et bien organisés 
| Couleurs harmonieuses (palette bleue) 
| Navigation intuitive 

---

## 🔄 Répartition des tâches

| Tâche | Responsable |
|-------|-------------|
| Structure HTML (7 pages) | [Prénom NOM 1] |
| CSS externe (style.css) | [Prénom NOM 1 / NOM 3] |
| JavaScript DOM + AJAX (app.js) | [Prénom NOM 1 / NOM 2] |
| Validation des formulaires | [Prénom NOM 1] |
| Backend PHP (auth/) | [Prénom NOM 2] |
| Backend PHP (rides/) | [Prénom NOM 2] |
| Base de données MySQL | [Prénom NOM 2] |
| Tests et débogage | [Prénom NOM 3] |
| README + Documentation | [Prénom NOM 3] |



---

## 🛠️ Technologies utilisées

- **Frontend :** HTML5 sémantique, CSS3 (variables, Grid, Flexbox, animations), JavaScript ES6+ natif
- **Backend :** PHP 8.x natif (PDO, password_hash, transactions MySQL)
- **Base de données :** MySQL 8.x
- **Carte interactive :** Leaflet.js + OpenStreetMap (sans clé API)
- **Serveur local :** XAMPP (Apache + MySQL)
