# Voyage

Proposition de destination de voyage que l’utilisateur connecté peut ajouter à sa liste de lieux rêvés ou déjà visités.

## Prérequis

- Docker & Docker Compose
- Git

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/CamileGhastine/voyage.git
cd voyage
```

### 2. Lancer les containers Docker

```bash
docker compose up -d
```

### 3. Installer les dépendances

```bash
docker exec symfony_php composer install
```

### 4. Créer la base de données

```bash
docker exec symfony_php php bin/console doctrine:migrations:migrate
```

### 5. Charger les fixtures

```bash
docker exec symfony_php php bin/console doctrine:fixtures:load --no-interaction
```

L'application est disponible sur [http://localhost:8080](http://localhost:8080).