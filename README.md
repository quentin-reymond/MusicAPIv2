# 🎵 MusicAPIv2

API REST développée en **PHP avec Slim Framework** permettant de gérer des artistes, des albums et leurs évaluations.

Le projet a été réalisé dans le cadre d'un projet scolaire et utilise une base de données **MySQL**.

---

## 📋 Présentation

**MusicAPIv2** est une API REST permettant de :

- 🎤 gérer les artistes
- 💿 gérer les albums
- ⭐ gérer les évaluations
- 🔎 rechercher des artistes et des albums
- 🔗 consulter les relations entre artistes, albums et évaluations
- 📊 calculer la moyenne des évaluations d'un album
- 🔐 protéger certaines routes avec une authentification **JWT**

L'API peut être testée avec **Postman**.

---

## 🛠️ Technologies utilisées

| Technologie | Utilisation |
|---|---|
| PHP | Langage principal |
| Slim Framework | Framework REST |
| MySQL | Base de données |
| PDO | Connexion à la base de données |
| PHP-DI | Injection de dépendances |
| JWT | Authentification |
| Composer | Gestion des dépendances |
| PHPUnit | Tests |
| Postman | Tests de l'API |
| WAMP | Environnement de développement local |
| AlwaysData | Hébergement distant |

---

## 🏗️ Architecture

Le projet utilise une organisation inspirée de l'architecture **Clean Architecture** :

```text
MusicAPIv2/
│
├── app/
│   ├── dependencies.php
│   ├── middleware.php
│   ├── repositories.php
│   ├── routes.php
│   └── settings.php
│
├── public/
│   └── index.php
│
├── src/
│   ├── Application/
│   │   ├── Actions/
│   │   ├── Handlers/
│   │   ├── Middleware/
│   │   ├── Repositories/
│   │   ├── ResponseEmitter/
│   │   └── Settings/
│   │
│   ├── Domain/
│   │   └── User/
│   │
│   ├── Infrastructure/
│   │   └── Persistence/
│   │
│   ├── Middleware/
│   │   ├── JwtHelper.php
│   │   └── JwtMiddleware.php
│   │
│   └── routes/
│       └── routesJWT.php
│
├── tests/
│
├── .env
├── .gitignore
├── composer.json
├── phpunit.xml
└── README.md
```

> Le fichier `.env` contient les informations sensibles et n'est pas versionné sur GitHub.

---

# 🚀 Installation

## Prérequis

Avant d'installer le projet, il faut disposer de :

- PHP 8 ou supérieur
- Composer
- MySQL
- Git
- Un serveur local comme WAMP ou XAMPP

---

## 1. Cloner le projet

```bash
git clone https://github.com/quentin-reymond/MusicAPIv2.git
```

Puis :

```bash
cd MusicAPIv2
```

---

## 2. Installer les dépendances

```bash
composer install
```

---

## 3. Configurer la base de données

Créer un fichier `.env` à la racine du projet.

Exemple :

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=music
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

⚠️ Ne jamais publier les identifiants de connexion à la base de données.

---

## 4. Lancer le projet

Avec un serveur local comme WAMP, placer le projet dans le dossier `www`.

L'API est ensuite accessible depuis :

```text
http://localhost/MusicAPIv2/
```

Selon la configuration du serveur, l'accès peut également se faire directement via le dossier `public`.

---

# 🌐 API en ligne

Une version distante de l'API est disponible sur AlwaysData :

```text
https://qre.alwaysdata.net
```

Exemple :

```text
GET https://qre.alwaysdata.net/artists
```

---

# 📚 Routes disponibles

## 🎤 Artists

### Récupérer tous les artistes

```http
GET /artists
```

### Récupérer un artiste

```http
GET /artists/{id}
```

### Rechercher un artiste

```http
GET /artists/search?name=Michel
```

### Récupérer les albums d'un artiste

```http
GET /artists/{id}/albums
```

### Créer un artiste

```http
POST /artists
```

Body JSON :

```json
{
  "Name": "Nom de l'artiste",
  "Annee": "2026",
  "Description": "Description de l'artiste"
}
```

### Modifier un artiste

```http
PUT /artists/{id}
```

Body JSON :

```json
{
  "Name": "Nouveau nom",
  "Annee": "2026",
  "Description": "Nouvelle description"
}
```

### Supprimer un artiste

```http
DELETE /artists/{id}
```

---

# 💿 Albums

### Récupérer tous les albums

```http
GET /albums
```

### Récupérer un album

```http
GET /albums/{id}
```

### Rechercher un album

```http
GET /albums/search?titre=annecy
```

### Récupérer les albums d'un artiste

```http
GET /artists/{id}/albums
```

### Récupérer les évaluations d'un album

```http
GET /albums/{id}/ratings
```

### Calculer la moyenne d'un album

```http
GET /albums/{id}/average-rating
```

Cette route retourne notamment :

- le nom de l'album
- la moyenne des notes
- le nombre de notes

### Créer un album

```http
POST /albums
```

Body JSON :

```json
{
  "Titre": "Nom de l'album",
  "Artist_idArtist": 1
}
```

### Modifier un album

```http
PUT /albums/{id}
```

Body JSON :

```json
{
  "Titre": "Nouveau titre",
  "Artist_idArtist": 1
}
```

### Supprimer un album

```http
DELETE /albums/{id}
```

---

# ⭐ Ratings

### Récupérer toutes les évaluations

```http
GET /ratings
```

### Récupérer une évaluation

```http
GET /ratings/{id}
```

### Créer une évaluation

```http
POST /ratings
```

Body JSON :

```json
{
  "Grade": 5,
  "Albums_idAlbums": 1
}
```

### Modifier une évaluation

```http
PUT /ratings/{id}
```

Body JSON :

```json
{
  "Grade": 4,
  "Albums_idAlbums": 1
}
```

### Supprimer une évaluation

```http
DELETE /ratings/{id}
```

---

# 🔐 Authentification JWT

L'API possède également un système d'authentification utilisant des **JSON Web Tokens (JWT)**.

## Connexion

```http
POST /login
```

Body :

```json
{
  "username": "SaintMichel",
  "password": "ITcampus"
}
```

La réponse contient un token :

```json
{
  "token": "..."
}
```

## Accéder à une route protégée

```http
GET /protected
```

Le token doit être envoyé dans l'en-tête HTTP :

```text
Authorization: Bearer <token>
```

Sans token valide, l'accès à la route est refusé.

---

# 🧪 Tests avec Postman

L'API peut être testée avec **Postman**.

Les principales fonctionnalités testables sont :

- GET des artistes
- GET des albums
- GET des évaluations
- recherches
- relations artistes/albums
- relations albums/évaluations
- création de ressources
- modification de ressources
- suppression de ressources
- calcul des moyennes
- authentification JWT
- accès aux routes protégées

---

# 🧪 Tests PHPUnit

Les tests automatisés se trouvent dans :

```text
tests/
```

Pour lancer les tests :

```bash
composer test
```

ou, selon la configuration :

```bash
vendor/bin/phpunit
```

---

# 🔒 Sécurité

Les informations sensibles ne doivent pas être enregistrées dans Git.

Le fichier :

```text
.env
```

est volontairement ignoré par Git grâce au fichier `.gitignore`.

Les mots de passe et clés secrètes doivent être stockés dans les variables d'environnement.

---

# 📦 Dépendances

Les dépendances PHP sont gérées avec Composer.

Installation :

```bash
composer install
```

Mise à jour :

```bash
composer update
```

---

# 👨‍💻 Auteur

**Quentin Reymond**

Projet réalisé dans le cadre d'une formation en informatique.

---

## 📄 Licence

Projet réalisé à des fins pédagogiques.