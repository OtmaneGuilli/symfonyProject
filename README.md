# symfonyProject
💼 Projet Symfony : InvoicePro – Gestion de Factures et Clients
🔧 Installation et Préparation de l’Environnement
1. Clonage du projet
Le projet est hébergé sur GitHub. Pour le cloner :

bash
Copier
Modifier
git clone https://github.com/OtmaneGuilli/symfonyProject.git
cd symfonyProject/projet
2. Installation des dépendances PHP
On utilise Composer pour installer toutes les bibliothèques Symfony et les bundles nécessaires :

bash
Copier
Modifier
composer install
3. Installation des dépendances front-end
L’application utilise Webpack Encore. Il faut donc installer les paquets NPM :

bash
Copier
Modifier
npm install
npm run build
5. Création de la base de données et exécution des migrations
bash
Copier
Modifier
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
🧩 Structure du Projet
🧑‍💼 Entité User
L’entité User représente les utilisateurs. Chaque utilisateur a :

nom, prénom, email, identifiant, mot de passe.

Une relation OneToMany avec Client (chaque user peut avoir plusieurs clients).

Authentification gérée via Symfony Security.

Validation avec Assert et #[UniqueEntity] pour éviter les doublons d’identifiant.

🧾 Entité Client
Chaque client appartient à un utilisateur (relation ManyToOne). Les champs :

nom du gérant, raison sociale, ville, pays, téléphone...

Relation OneToMany avec Facture.

Un client ne peut pas être supprimé s’il possède des factures (sécurité gérée dans le contrôleur).

📄 Entité Facture
Une facture contient :

numéro (unique), date, montant, état (Payée, Non payée...), note.

Chaque facture appartient à un Client.

🔐 Authentification & Sécurité
Utilisation de Symfony Security :

Système d’inscription (RegistrationController)

Connexion via formulaire (SecurityController)

Chaque utilisateur ne peut voir que ses propres clients et factures

Routes sécurisées avec #[IsGranted('ROLE_USER')] dans les contrôleurs

🎮 Contrôleurs
🔑 SecurityController
Gère la connexion

Redirige vers la page client après login

👤 RegistrationController
Formulaire d’inscription d’un nouvel utilisateur

Hash du mot de passe avec UserPasswordHasherInterface

Redirection automatique vers login

📋 ClientController
Liste des clients d’un utilisateur connecté

CRUD complet (ajout, édition, suppression)

Vérifie que le client appartient bien au user avant chaque action

Affiche les factures d’un client (via bouton)

📜 FactureController
Liste toutes les factures de tous les clients de l’utilisateur

Ajout, édition, suppression d’une facture

Génère automatiquement un numéro de facture unique

Filtrage des clients possibles selon l’utilisateur

🎨 Vues (Twig)
Toutes les vues sont personnalisées et modernes grâce à Bootstrap :

base.html.twig : Template principal, avec navbar conditionnelle (connexion/déconnexion)

login.html.twig et register.html.twig : formulaire utilisateur

client/index.html.twig : tableau des clients avec options (voir, modifier, supprimer, facturer)

facture/index.html.twig : liste des factures avec états et montants

facture/by_client.html.twig : affichage des factures pour un seul client

home.html.twig : page d’accueil publique, style marketing avec hero section, features, pricing, etc.

🐳 Docker
Construction de l’image :
bash
Copier
Modifier
docker build -t otmane49/symfony-app .
Exécution :
bash
Copier
Modifier
docker run -d -p 8000:8000 --name symfony-app otmane49/symfony-app
L’image contient PHP 8.2, Composer, et toutes les extensions nécessaires.

📈 Fonctionnalités Clés Réalisées
✅ Authentification sécurisée

✅ Inscription avec validation des champs

✅ Création de clients

✅ Gestion des factures

✅ Relations entité User → Client → Facture

✅ Sécurité sur les routes

✅ Interface moderne avec Bootstrap

✅ Intégration Docker pour déploiement

✅ Navbar dynamique (selon login/logout)

✅ Page d'accueil publique responsive

🚀 Conclusion
Le projet InvoicePro est une application Symfony robuste et modulaire, conçue pour gérer les factures et les clients d’un utilisateur connecté, avec une sécurité renforcée et un design moderne. Il est prêt pour être déployé dans un environnement Docker ou intégré à une chaîne DevOps (SonarQube, Jenkins, DockerHub, Ansible...).