# Bilan technique du projet ATECH

Ce document résume l'architecture technique et les choix technologiques du projet ATECH, une plateforme de gestion d'interventions de cybersécurité.

---

## 1. Langages utilisés

| Nom | Version | Rôle dans le projet |
| :--- | :--- | :--- |
| PHP | ^8.3 | Langage serveur principal (Laravel). |
| JavaScript | ES6+ | Langage frontend (Vue.js) et build tool (Vite). |
| SQL | MySQL 8 | Langage de requête pour la base de données. |

**Justification :**
*   **PHP 8.3** : Choisi pour sa performance, son typage fort et sa compatibilité avec le dernier écosystème Laravel.
*   **JavaScript (ES6+)** : Standard industriel pour le développement frontend interactif.

---

## 2. Frameworks

| Nom | Version | Rôle dans le projet |
| :--- | :--- | :--- |
| Laravel | ^13.8 | Framework backend complet et robuste. |
| Vue.js | ^3.4 | Framework frontend pour l'interface utilisateur. |

**Justification :**
*   **Laravel** : Offre une structure robuste, une sécurité intégrée, et une grande productivité pour les applications métier complexes.
*   **Vue.js 3** : Framework réactif, léger et facile à intégrer dans Laravel, particulièrement via Inertia.js.

---

## 3. Bibliothèques Backend (Composer)

| Catégorie | Nom | Version | Rôle |
| :--- | :--- | :--- | :--- |
| **Interface** | `inertiajs/inertia-laravel` | ^2.0 | Communication entre Laravel et Vue.js. |
| **Auth** | `laravel/sanctum` | ^4.0 | Gestion d'authentification légère. |
| **PDF** | `barryvdh/laravel-dompdf` | ^3.1 | Génération de rapports d'intervention PDF. |
| **Routing** | `tightenco/ziggy` | ^2.0 | Accès aux routes Laravel dans Vue.js. |

**Justification :**
*   **Inertia.js** : Évite de construire une API JSON complexe, permettant de bâtir une application monolithique moderne avec la réactivité d'une SPA.
*   **Sanctum** : Solution officielle Laravel, simple et sécurisée, pour l'authentification.

---

## 4. Bibliothèques Frontend (NPM)

| Catégorie | Nom | Version | Rôle |
| :--- | :--- | :--- | :--- |
| **UI** | `tailwindcss` | ^3.2.1 | Framework CSS utilitaire pour le design. |
| **Graphiques**| `chart.js` / `vue-chartjs` | ^4.5 / ^5.3| Visualisation des données (tableaux de bord). |
| **Build** | `vite` | ^8.0 | Outil de build moderne et rapide. |

**Justification :**
*   **TailwindCSS** : Permet un design rapide, cohérent et hautement personnalisable sans écrire de CSS complexe.
*   **Chart.js** : Bibliothèque standard pour des graphiques performants et lisibles.

---

## 5. Environnement et Outils

| Catégorie | Nom | Rôle |
| :--- | :--- | :--- |
| **Base de données** | MySQL 8 | SGBD relationnel pour le stockage des données. |
| **Gestion versions**| Git | Suivi de l'historique et collaboration. |
| **Serveur Local** | Artisan Serve | Serveur de développement intégré à Laravel. |

**Justification :**
*   **MySQL 8** : Stabilité et robustesse pour les relations complexes du modèle de données (tickets, utilisateurs, évaluations).
