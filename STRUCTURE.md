# Structure du Projet

Ce document détaille l'organisation de cette application Laravel et vous guide pour débuter.

## 1. Comprendre l'organisation

- `app/` : C'est le cœur de votre application.
    - `Http/` : Gère les requêtes (Contrôleurs), la sécurité (Middleware) et la validation des formulaires.
    - `Models/` : Représente vos tables dans la base de données. Chaque fichier ici correspond à une table.
- `database/` :
    - `migrations/` : Les plans de construction de vos tables SQL.
- `resources/` : Ce que l'utilisateur voit.
    - `js/` : Tout votre Frontend (Vue.js, composants, pages).
- `routes/` : Le "standard téléphonique" qui dit : "Si l'URL est `/contact`, envoie la requête à tel contrôleur".

---

## 2. Guide pratique : Créer une nouvelle fonctionnalité

Pour ajouter une fonctionnalité (ex: "Articles" ou "Projets"), suivez cet ordre logique. Laravel facilite cela avec des commandes dans le terminal (à la racine du projet).

### Étape A : Créer le Modèle et la Migration
Le modèle permet de manipuler les données, la migration crée la table SQL.
```bash
php artisan make:model Article -m
```
*(Le `-m` crée automatiquement la migration associée).*

### Étape B : Définir la table (Migration)
Allez dans `database/migrations/xxxx_create_articles_table.php` et ajoutez vos colonnes :
```php
$table->string('title');
$table->text('content');
```
Puis, exécutez la migration pour créer la table :
```bash
php artisan migrate
```

### Étape C : Créer le Contrôleur
Il va gérer la logique (récupérer les données, les envoyer à la vue).
```bash
php artisan make:controller ArticleController
```

### Étape D : Créer une Route
Allez dans `routes/web.php` pour lier une URL à votre contrôleur :
```php
use App\Http\Controllers\ArticleController;
Route::get('/articles', [ArticleController::class, 'index']);
```

### Étape E : Créer la vue (Frontend)
Dans `resources/js/Pages/`, créez par exemple `Articles/Index.vue`.
Ensuite, dans votre `ArticleController`, retournez cette vue avec Inertia :
```php
public function index() {
    return inertia('Articles/Index');
}
```

---

## 3. Fichiers Racine Importants

- `artisan` : CLI de Laravel pour exécuter les commandes ci-dessus.
- `composer.json` : Gère vos dépendances PHP.
- `package.json` : Gère vos dépendances JavaScript (Vue, Tailwind).
- `.env` : **(Ne jamais partager)** Vos configurations secrètes (base de données, clés API).
