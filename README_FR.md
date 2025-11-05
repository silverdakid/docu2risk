# Docu2Risk - Application d'Analyse Automatisée de Risques Inhérents Financiers

## Contexte du Projet

Il est important de noter que **Docu2Risk est un projet universitaire** réalisé en groupe de cinq personnes dans le cadre de notre formation en BUT Informatique à l'IUT de Metz.

L'application a été développée en équipe, et les tâches ont été réparties entre les membres.

Ma contribution personnelle s'est principalement concentrée sur le **cœur technique de l'analyse (Backend Python)** :

* **Algorithme d'Analyse** : Conception et développement de l'algorithme Python gérant l'extraction d'informations.
* **Intégration de l'IA** : Mise en place de l'API de **ChatGPT** (OpenAI) pour l'extraction des données textuelles des documents.
* **Développement du Web Scraping** : Création des scripts (`requests`, `BeautifulSoup`) pour collecter des données externes (risque pays, listes noires, etc.).
* **Mise en place de l'OCR** : Intégration de **Tesseract** pour extraire le texte des documents scannés (PDF non natifs).
* **Création de l'API Backend** : Développement de l'**API Flask** servant de pont entre le frontend PHP et le moteur d'analyse Python.
* **Prétraitement** : Conception de l'algorithme de prétraitement pour les questionnaires Wolfsberg, afin de ne cibler que les pages pertinentes.
* **Participation Front-End** : Contribution au développement des vues en HTML et CSS.

---

Docu2Risk est une application web conçue pour aider les entreprises du secteur financier à **automatiser l'analyse du risque inhérent** d'une contrepartie. L'application vise à transformer une tâche traditionnellement manuelle, fastidieuse et chronophage en un processus rapide et assisté par l'IA.

Les utilisateurs peuvent téléverser des documents financiers standards (tels que les questionnaires `Wolfsberg`, `ICI` ou des `Factures`) pour obtenir en quelques étapes un rapport d'analyse complet, incluant un score de risque final.

## Fonctionnalités

* **Analyse de Documents Multi-formats** : Prise en charge des documents financiers clés (Wolfsberg, ICI) pour l'extraction d'informations.
* **Extraction par IA** : Utilisation de l'IA pour lire les documents et répondre automatiquement à un questionnaire d'analyse prédéfini.
* **Enrichissement par Web Scraping** : Collecte automatique de données externes (risque politique du pays, listes noires des autorités financières, fuites de données) pour affiner l'analyse.
* **Validation Manuelle** : Interface permettant à l'utilisateur de vérifier, corriger ou compléter les réponses fournies par l'IA avant de générer le score.
* **Rapports Détaillés** : Génération d'un score final (Faible / Moyen / Élevé) basé sur un barème pondéré, avec la possibilité de voir le détail des points.
* **Historique des Analyses** : Conservation d'un historique de toutes les analyses effectuées pour un suivi dans le temps.
* **Export des Données** : Possibilité d'exporter les résultats de l'analyse au format `.csv`.
* **Gestion des Rôles** : L'application gère trois niveaux d'utilisateurs (Lambda, Chef de projet, Administrateur) avec des droits d'accès distincts.

## Explications Techniques

### Architecture

L'application repose sur une architecture client-serveur dissociée :

1.  **Frontend / Portail Web (PHP)** : Développé en **PHP** selon une structure **MVC (Modèle-Vue-Contrôleur)**. Il gère l'interface utilisateur, l'authentification, la gestion des comptes et la présentation des résultats.
2.  **Backend / Moteur d'Analyse (Python)** : Un service distinct développé avec le framework **Flask**. Il expose plusieurs *endpoints* qui reçoivent les documents envoyés par le PHP, effectuent tous les traitements lourds (OCR, IA, Scraping) et renvoient les résultats au format JSON.

### Base de Données

Nous utilisons **MariaDB** comme système de gestion de base de données.

### Algorithmes de Détection

Pour détecter les informations qui signalent un risque, Docu2Risk combine plusieurs techniques :

* **OCR (Tesseract)** : La bibliothèque `ocrmypdf` (basée sur Tesseract) est utilisée pour extraire le texte brut des fichiers PDF scannés, les rendant ainsi lisibles par l'IA.
* **IA (ChatGPT API)** : L'api de ChatGPT est utilisée pour sonder le texte extrait. Des *prompts* spécifiques sont envoyés avec le contenu du document pour que l'IA identifie et retourne les réponses aux questions d'analyse.
* **Web Scraping (BeautifulSoup)** : Pour les données externes, des scripts Python ciblent des sites web précis (ex: TradingEconomics, Pappers, autorités financières) pour en extraire les informations pertinentes (risque pays, SIRET, etc.).

### Frontend

L'interface utilisateur est construite en **HTML**, **CSS** et **JavaScript/TypeScript**.


### 3. Note Importante (État Actuel)

**Le projet n'est actuellement pas fonctionnel en l'état.**

La base de données **MariaDB** était hébergée sur l'instance **PhpMyAdmin** fournie par l'IUT de Metz. À la fin du projet, cet environnement a été réinitialisé et la base de données n'a pas pu être récupérée.

Pour rendre le projet fonctionnel, il serait nécessaire de :
1.  Recréer le schéma de la base de données MariaDB
2.  Adapter les fichiers de configuration PHP (DAO) pour pointer vers la nouvelle base de données.