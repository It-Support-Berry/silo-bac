# Traça_Astra
Le but était  de mettre en place une application web simple d'utilisation, permettant aux utilisateurs (Réceptionniste, chef d'équipe, admin, auditeur) d'avoir un système flexible et de traçabilité  pour assurer le contrôle continu.

** 1. Réceptionniste :**
- Remplir un formulaire dès la réception d'un lot qui sera affiché pour l'ensemble des utilisateurs;
- Clôturer un lot si besoin et un email de notification sera envoyé aux atéliers afin qu'ils puissent débuter un nouveau lot. 
- Modifier la date de réception et le numéro de lot si nécessaire 
- Consulter les archives, faire une recherche par date de réception, par CODEJDE, par matière,  par numéro de lot et télécharger l'archive en totalité ou après la recherche.

** 2. Chef d'équipe :**
 - Débuter ou  Clôtuer un lot en cours d'utilisation.
 - Créer un nouveau lot si besoin en reprenant les informations du lot archivé pour vider la cuve.
 - Consulter les archives, faire une recherche par date de réception, par CODEJDE, par matière,  par numéro de lot et télécharger l'archive en totalité ou après la recherche.
 
 ** 3. Admin :**
 - Créer, éditer, supprimer : cuve, silo, matiere, codejde et atelier. 
 - Consulter les archives, faire une recherche par date de réception, par CODEJDE, par matière,  par numéro de lot et télécharger l'archive en totalité ou après la recherche.
 - Modifier la date de réception ou le numéro de lot d'une archive en cas d'erreur de saisie de la part du réceptionniste en cocervant les anciennes informations. 

## Technologies : 
- Symfony 5
- Xamp verion 3
- Twig 
- kpn paginator 
- Swift_mailer
- Bootstrap 5 
- EasyAdmin (Dashboard)

##Controller : 
#### 1. **ControleController :** 
gère l'entité controle :
- **index **: affiche le formulaire pour enregister un nouveau lot en l'enregistrement en bdd. 
- **update_silo_reception : ** permet à un réceptionniste de modifier un lot qui en cours d'utilisation ou de clôturer un lot en envoyant un mail automatique de notification aux atéliers. 
- **update_silo_chef : ** permet à un chef d'équipe de débuter en envoyant un mail automatique  ou clôtuer un lot. 
- ** view :** permet aux utilisateurs de visualiser les détails d'un lot en cours d'utilisation sans pouvoir appliquer aucune action. 
- **getCuves : ** permet de récupérer de façon dynamique les cuves à partir du select d'un silo sur le formulaire d'enregisrement d'un nouveau lot.
- **getMatiere : ** permet de récupérer de façon dynamique la matière à partir du select d'un codejde sur le formulaire d'enregisrement d'un nouveau lot.

#### 2. **ArchiveController :** 
gère l'entité controle :
-** index :** permet d'afficher les silos qui  sont clôturés
- **archive_silo_update :** permet de modifier la date et le numéro de lot  d'une archive avec le rôle admin. 
- **archive_silo_details :**  permet aux utilisateurs de visualiser les détails d'un lot clôturé sans pouvoir appliquer aucune action. 

- **archive_silo_download :**  permet de télécharger l'ensemble des archives ou après avoir appliquer une recherche par date, par lot, par codejde ou encore par matière.

- **details_download:** permet de télécharger les détails d'un seul lot archivé.

#### 3. **RegistrationController :**  
permet d'inscrire un utilisateur avec le rôle selectionné. 

#### 4. **ResetPasswordController :**  
Gère la rénitialisation du mot de passe d'un utilisateur en envoyant un mail avec le lien.

#### 5. **SecurityController :**  
Gère le contrôle des identifiants lors de la connexion et la déconnexion.

#### 6. **SacController :**  
S'occupe de la gestion des bacs (sacs), il alimente 2 entités: 
- **sacs:** qui contient les bacs en cours d'utilisation
- **archives:** qui contient tous les bacs enregistrés.
##### Dans classe on a les methodes : 
- **index:** qui affiche la page d'accueil pour les bacs en cours; 
- **createSac:** qui affiche la page de création du lot de bac; 
- **archivesSac:** affiche la page où tous les lots de bacs sont enregistrés ; 
- **archive_silo_download:** permet de télécharger l'ensemble des archives ou en fonction du filtre appliqué. 


## Modal : 
- **filtreDate:** permet d'afficher sur la page des archives le formulaire de recherche  pour les silos (par date, lot, matière et codejde)
et pour les bacs (par date, lot1, lot2, matière et codejde)



#Ajout / completer le readme


### End