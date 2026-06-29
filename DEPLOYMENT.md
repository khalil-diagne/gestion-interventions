# Guide de Déploiement : Laravel sur AWS Linux (Ubuntu)

Ce guide décrit les étapes pour déployer votre application Laravel sur une instance AWS Linux (type Ubuntu).

## 1. Prérequis sur le Serveur
Connectez-vous à votre serveur SSH : `ssh ubuntu@votre-ip-aws`

Mettez à jour le système :
```bash
sudo apt update && sudo apt upgrade -y
```

## 2. Installation de la Stack (LEMP)
```bash
# Installation de Nginx, PHP et MySQL
sudo apt install nginx php-fpm php-mysql php-xml php-mbstring php-curl php-zip php-bcmath git unzip -y
sudo apt install mysql-server -y
```

## 3. Préparation du Projet sur le Serveur
```bash
# Cloner votre projet (remplacez par votre repo)
cd /var/www/
sudo git clone <votre-url-git> gestion-interventions
sudo chown -R ubuntu:www-data /var/www/gestion-interventions
cd gestion-interventions
```

## 4. Installation des Dépendances
```bash
# Installer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installer les dépendances PHP
composer install --optimize-autoloader --no-dev

# Installer les dépendances JS et compiler
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y
npm install
npm run build
```

## 5. Configuration de l'Application
```bash
cp .env.example .env
# Modifiez le .env avec vos accès BD et APP_KEY
nano .env

# Générer la clé et configurer
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

## 6. Configuration Nginx
Créez le fichier de config : `sudo nano /etc/nginx/sites-available/gestion-interventions`
Contenu type (adaptez le `server_name`) :
```nginx
server {
    listen 80;
    server_name votre_domaine_ou_ip;
    root /var/www/gestion-interventions/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # Vérifiez votre version PHP
    }
}
```
Activez le site :
```bash
sudo ln -s /etc/nginx/sites-available/gestion-interventions /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## 7. Sécurité & Permissions
```bash
sudo chown -R www-data:www-data /var/www/gestion-interventions/storage /var/www/gestion-interventions/bootstrap/cache
sudo chmod -R 775 /var/www/gestion-interventions/storage /var/www/gestion-interventions/bootstrap/cache
```
