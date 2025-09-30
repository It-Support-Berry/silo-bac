# 🚀 Backend-zeller-planner – API de Traçabilité Industrielle

> Le but de ce projet est de mettre en place une application web simple d'utilisation, permettant aux utilisateurs (Réceptionniste, Chef d'équipe, Admin, Auditeur) d'avoir un système flexible et de traçabilité pour assurer le contrôle continu.

---

![Symfony](https://img.shields.io/badge/Symfony-5.4-black)
![Twig](https://img.shields.io/badge/Twing-2.12-yellow)
![EasyAdmin](https://img.shields.io/badge/EasyAdmin-3.5-blue)


## 🛠 Technologies utilisées

- Symfony
- XAMPP version 
- Twig (moteur de templates)
- KNP Paginator (pagination)
- Swift Mailer (envoi d’emails)
- Bootstrap 5 (front-end)
- EasyAdmin (Dashboard admin)
---

## 🎯 Fonctionnalités par rôle

### 1. Réceptionniste
- Remplir un formulaire dès la réception d'un lot, affiché pour l’ensemble des utilisateurs.
- Clôturer un lot si besoin, avec envoi automatique d’un email de notification aux ateliers pour débuter un nouveau lot.
- Modifier la date de réception et le numéro de lot si nécessaire.
- Consulter les archives, effectuer des recherches par date de réception, CODEJDE, matière, numéro de lot, et télécharger les archives (totales ou filtrées).

### 2. Chef d’équipe
- Débuter ou clôturer un lot en cours d’utilisation.
- Créer un nouveau lot en reprenant les informations d’un lot archivé pour vider la cuve.
- Consulter les archives, faire des recherches et télécharger les archives (totales ou filtrées).

### 3. Admin
- Gérer les entités : cuve, silo, matière, codejde, atelier (création, édition, suppression).
- Consulter les archives, effectuer des recherches et télécharger les archives.
- Modifier la date de réception ou le numéro de lot d’une archive en conservant les anciennes informations (correction d’erreurs de saisie).

### 4. Auditeur
- Consulter les activités et les lots en cours ou archivés.  
- Télécharger les rapports et archives disponibles.  
- Recevoir en copie les emails de notification envoyés lors des actions importantes (clôture/début de lots, etc.).

---

## 📂 Architecture et Contrôleurs principaux

### 1. SiloController
Gère l’entité `controle` (lots en cours) :  
- `index` : formulaire et enregistrement d’un nouveau lot.  
- `update_silo_reception` : modification/clôture d’un lot par le réceptionniste + mail notification.  
- `update_silo_chef` : début/clôture d’un lot par chef d’équipe + mail notification.  
- `view` : visualisation des détails d’un lot sans modification possible.  
- `getCuves` : récupération dynamique des cuves selon le silo sélectionné.  
- `getMatiere` : récupération dynamique de la matière selon le codejde sélectionné.

### 2. ArchiveController  
Gère les lots archivés :  
- `index` : affichage des silos clôturés.  
- `archive_silo_update` : modification date et numéro de lot par admin (avec conservation historique).  
- `archive_silo_details` : visualisation des détails d’un lot archivé.  
- `archive_silo_download` : téléchargement des archives (totales ou filtrées).  
- `details_download` : téléchargement des détails d’un lot archivé.

### 3. RegistrationController  
Gestion de l’inscription des utilisateurs avec rôle assigné.

### 4. ResetPasswordController  
Gestion de la réinitialisation des mots de passe via email.

### 5. SecurityController  
Gestion des connexions/déconnexions.

### 6. BacController  
Gestion des bacs (sacs) et leurs archives :  
- `index` : liste des bacs en cours.  
- `createSac` : création d’un nouveau lot de bacs.  
- `archivesSac` : affichage des archives des bacs.  
- `archive_silo_download` : téléchargement des archives de sacs.

---


## 📝 Modals
- `filtreDate` : formulaire de recherche sur les archives (silos et bacs) par date, lot, matière et codejde.

---

## 🔧 Installation & démarrage rapide
1. **Cloner le projet**

- git clone https://github.com/It-Support-Berry/silo-bac.git

2. **installer les dépendances**

  - cd projet_traca
  - composer install


## Stockage de code source

Le code source est déployé sur le serveur **a576fr10 (5.76.9.10)**, à l'emplacement suivant : **C:\Apache24\htdocs\projet_traca**

---

## 🚀 Déploiement (manuel)

Pour déployer l'API backend et configurer Apache, voici les étapes principales :

### Récupération du code (en production)

Sur le serveur de production, ouvrir le dossier du projet et exécuter la commande suivante pour récupérer les dernières modifications :

```
  cd C:\Apache24\htdocs\projet_traca
  git pull origin main

```

### Configuration Apache

Pour configurer Apache, il faut gérer 3 fichiers importants :

- **httpd.conf**  
  Chemin : `C:\Apache24\conf\httpd.conf`  
  - Ajouter la configuration PHP (module, extension, etc.)  
  - Définir le numéro de port d'écoute (84)  

- **httpd-vhosts.conf**  
  Chemin : `C:\Apache24\conf\extra\httpd-vhosts.conf`  
  - Ajouter un VirtualHost pointant vers le dossier de l’application backend  
  - Définir le nom de domaine (ServerName) utilisé pour accéder à l'application  
  - Configurer les restrictions d’accès (ex : par IP, authentification, etc.)  

- **hosts**  
  Chemin : `C:\Windows\System32\drivers\etc\hosts`  
  - Ajouter une entrée pour associer le nom de domaine configuré dans Apache à l’adresse IP du serveur local (ex : `127.0.0.1 mon-api-zeller.local`)

---

## Exemple minimal de VirtualHost à ajouter dans `httpd-vhosts.conf` :

```apache
<VirtualHost 5.76.9.10:80>
    ServerName a576fr10

    DocumentRoot "C:/Apache24/htdocs/projet_traca/public"
    <Directory "C:/Apache24/htdocs/projet_traca/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

</VirtualHost>

```

---

## 👤 Auteurs & contact

🧑‍💻 **Développeur ** : Lansana KEITA
📧 **Contact** : [lansanakeita@berryglobal.com](mailto:lansanakeita@berryglobal.com)  
