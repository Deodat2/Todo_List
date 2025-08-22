# ✅ Todo_List App - Application de gestion de tâches en PHP

Une application web simple mais bien structurée qui permet à chaque utilisateur de gérer ses tâches personnelles. L'utilisateur peut s'enregistrer, se connecter, voir un dashboard personnalisé, créer/modifier/supprimer des tâches avec des tags, et filtrer ses tâches par tag.

---

## 📚 Fonctionnalités

- ✅ Authentification (inscription, connexion, déconnexion)
- 🧑‍💼 Dashboard personnel par utilisateur
- 🗂️ CRUD complet des tâches
- 🏷️ Attribution de tags à chaque tâche
- 🔎 Filtrage des tâches par tag

---

## ⚙️ Technologies utilisées

- PHP
- MySQL
- HTML5 / CSS3 / un peu de JavaScript
- Git (Versionnage)

---

## 🧪 Installation et configuration

### 1. Cloner le repo

```bash
git clone https://github.com/Deodat2/Todo_List.git
cd Todo_List
```

### 2. Renommer le fichier .env.example en .env

### 3. Modifier les variables selon votre configuration locale

```env
DB_HOST=127.0.0.1
DB_NAME=
DB_USER=
DB_PASS=
```

### 4. lancer XAMPP ou WAMP (ou le service MySQL si tu l’as installé seul).

### 5. Créer la base de données

```bash
php migrate
```

### 5. Lancer le serveur local

```bash
php -S localhost:8000 -t public/
```

### 6. Puis accéder à http://localhost:8000 dans votre navigateur.