# RH Management

- https://www.youtube.com/watch?v=bUEZqpgAvQM

- Plateforme de gestion des ressources humaines. Le genre d'application qu'une véritable PME utiliserait pour gérer ses équipes au quotidien.
  - Annuaire des employés avec photos et profils,
  - gestion des services et des postes,
  - processus de demande et de validation des congés
  - système de pointage (arrivée/départ), de feuilles de temps, de fiches de paie mensuelles imprimables
  - un tableau de bord avec des graphiques en temps réel et de rapports CSV pour les audits.

#### Browser/Client :
- **React 19** + **Tailwind 4** :
  - s'exécute sur le navigateur client
  - donne vie à l'interface (ouvre une modale, filtre une liste, affiche une notification le tout sans recharger la page entière)
- **Shadcn**

#### The bridge pour connecter Backend & Frontend :
- **Inertia JS** :
  - lien entre Laravel et React : habituellement, connecter un backend PHP à un frontend JavaScript implique de créer une API distincte pour échanger des données JSON dans les 2 sens. Deux projets donc 2 fois plus de travail. Inertia supprime cela.
  - passe Data et Props,
  - rend les pages React,
  - pas d'API distincte

#### Server :
- **Laravel 13** :
  - s'exécute sur le serveur (PHP),
  - gère le routage des URL
  - gère le flux de données,
  - enregistre/lit depuis la BDD
  - gère la vérification des mots de passe
  - gère la validation des formulaires
  - Laravel transmet directement les données à un composant React sous forme de « props », un peu comme on passe une assiette d'un 	bout à l'autre d'une table. Nous continuons à écrire des contrôleurs Laravel classiques. Nous nous contentons de rendre une page React au lieu d'un modèle HTML traditionnel

#### Database :
- **SQLite** :
  - conserve toute la base de données dans un seul fichier sur le disque. Il n'y a pas de serveur de base de données distinct à installer ou à configurer ce qui est parfait pour l'apprentissage et, honnêtement, tout à fait adapté à de nombreuses 	applications réelles également.
  - enregistre les données

#### Exécution locale du projet :
- **Laravel Herd**

- Création du projet "rh-management" directement dans Herd :
  - Add
  - Laravel New project
  - React

- Le site est créé à cette adresse : http://rh-management.test

- Pendant le développement, on laisse tourner un serveur de dev ainsi nos fichiers React et Tailwind se recompilent automatiquement à chaque sauvegarde.
- Dans **phpStorm** :
  - ouvrir le terminal et taper "npm run dev"

- **Github** :
  - Enregistrer le projet dans Github

#### La structure du projet :
- "app" pour le backend
- "database" pour les données
- "routes" pour le routing
- "resources/js" pour React

#### La conception du projet :
- La compétence la plus importante pour créer une véritable application consiste à observer le monde réel — souvent chaotique — et à le transformer en un ensemble cohérent de tables reliées entre elles. 

- EMPLOYEE TABLE
  - name, email, phone, hire date, salary, status (active/on leave)

- Chaque employé est rattaché à un département.
- DEPARTMENT TABLE
  - Engineering, Sales ...

- Et y occupe un poste
- POSITION TABLE
  - Software Engineer, Sales Manager ...

- Chaque employé est lié à une entrée de chacune de ces 2 tables Department et Position
- Un employé peut être sous la responsabilité d'un manager qui est lui-même un employé. On a la table Employee qui fait alors référence à elle-même

- Les congés : besoin de type de congés comme les congés payés, les arrêts maladie.
- LEAVE TYPE TABLE
  - Annual
  - Sick

- Chaque employé dispose d'un solde de congés (nombdre de jours auxquels il a droit, ceux qu'il a déja pris par type et par an). Lorsqu'il souhaite prendre des congés, il dépose une demande qui est initialement en attente avant d'être approuvée ou rejetée. Cela représente trois tables supplémentaires fonctionnant de concert.
- LEAVE BALANCE TABLE 
  - Entitlement
  - Used
  - per Type/Year
- LEAVE REQUEST TABLE
  - Pending
  - Approved/Rejected

- La gestion des présences: une ligne par employé et par jour, enregistrant les heures d'arrivée et de départ.
- ATTENDANCE TABLE
  - Clock In/Out
  - per Employee
  - per Day

- Les fiches de paye : une ligne par employé et par période de paie, indiquant le salaire brut, les retenues et le salaire net.
- PAYLIPS TABLE
  - Gross Pay
  - Deductions
  - Net pay
  - per pay Period

- Enfin, les utilisateurs connectés se voient attribuer un rôle (administrateur, RH, manager ou employé), ce qui détermine ce que chacun est autorisé à voir.
- USER ROLES TABLE
  - Admin
  - HR
  - Manager
  - Employee

## Migrations & Eloquent Models : The Data Foundation
- Transformer ces tables en véritable tables de BDD avec une classe PHP correspondant, les models. On a 9 tables donc 9 models.




























