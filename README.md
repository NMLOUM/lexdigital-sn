# LexDigital SN

**Système d'Analyse des Risques Juridiques des Plateformes Numériques au Sénégal**

Projet académique — ESITEC L3 Génie Logiciel · Groupe Supdeco Dakar · 2026

---

## Présentation

LexDigital SN est une application web qui permet aux opérateurs de plateformes numériques au Sénégal d'évaluer leurs risques juridiques et d'obtenir des recommandations de mise en conformité, sans nécessiter de connaissances juridiques préalables.

L'outil couvre **9 domaines juridiques** fondés sur le cadre légal sénégalais en vigueur :

| Domaine | Textes de référence |
|---|---|
| Protection des données personnelles | Loi n°2008-12, Décret 2008-721 |
| Contrats électroniques et CGU | Loi n°2008-08 |
| Paiement électronique et monnaie mobile | Instructions BCEAO 008/2002, 03/2012, 01/2018 |
| Cybersécurité et incidents | Loi n°2008-11 / Code pénal art. 431-10 à 431-40 |
| Contenu illicite et modération | Loi n°2008-11, Loi n°2017-27 (Code de la presse) |
| Protection des consommateurs | Loi n°83-01, COCC art. 92-99 |
| Propriété intellectuelle | Loi n°2008-09, Accord de Bangui / OAPI |
| Fiscalité du numérique | CGI Sénégal, Directive UEMOA 02/2011 |
| Protection des mineurs en ligne | Code pénal art. 431-35 à 431-40, CIDE |

### Fonctionnalités

- Formulaire guidé : type de plateforme, domaine juridique, contexte géographique, aspects techniques
- Moteur d'analyse par matrice de règles métier (sans IA externe)
- Rapport personnalisé : niveau de risque, textes applicables, recommandations, sanctions, autorité compétente
- Export PDF du rapport
- Authentification avec espace personnel (historique des analyses par utilisateur)
- Dashboard avec statistiques et filtres par niveau de risque

---

## Stack technique

- **Backend** : Laravel 9 / PHP 8
- **Base de données** : MySQL
- **Authentification** : Laravel Breeze
- **PDF** : barryvdh/laravel-dompdf
- **Frontend** : Blade, CSS personnalisé (IBM Plex Sans, IBM Plex Mono, Playfair Display)

---

## Installation

### Prérequis

- PHP >= 8.0
- Composer
- MySQL
- Node.js (optionnel)

### Étapes

**1. Cloner le dépôt**

```bash
git clone https://github.com/NMLOUM/lexdigital-sn.git
cd lexdigital-sn
```

**2. Installer les dépendances PHP**

```bash
composer install
```

**3. Configurer l'environnement**

```bash
cp .env.example .env
php artisan key:generate
```

Ouvrez `.env` et configurez votre base de données :

```env
DB_DATABASE=legalrisk
DB_USERNAME=root
DB_PASSWORD=
```

**4. Créer la base de données et exécuter les migrations**

```bash
php artisan migrate
```

**5. Lancer le serveur de développement**

```bash
php artisan serve
```

L'application est accessible sur **http://127.0.0.1:8000**

---

## Utilisation

1. Créer un compte via `/register`
2. Se connecter via `/login`
3. Remplir le formulaire d'analyse (type de plateforme, domaine juridique, situation)
4. Consulter le rapport généré
5. Télécharger le rapport en PDF si nécessaire
6. Retrouver toutes ses analyses dans le Dashboard

---

## Structure du projet

```
app/
├── Http/
│   ├── Controllers/
│   │   └── AnalyseController.php   # Moteur d'analyse + CRUD
│   └── Requests/
│       └── AnalyseRequest.php      # Validation du formulaire
└── Models/
    └── Analyse.php                 # Modèle avec cast JSON pour aspects[]

resources/views/
├── layouts/app.blade.php           # Layout principal
├── analyse.blade.php               # Formulaire d'analyse
├── resultat.blade.php              # Rapport de résultat
├── dashboard.blade.php             # Historique utilisateur
├── auth/                           # Login / Register
└── pdf/rapport.blade.php           # Template export PDF

database/migrations/                # Migrations SQL
routes/web.php                      # Routes (toutes protégées par auth)
```

---

## Fonctionnement du moteur d'analyse juridique

### Principe général

Le moteur d'analyse ne repose sur aucune intelligence artificielle externe. Il fonctionne comme un **système expert à règles métier** : un ensemble de règles juridiques construites manuellement à partir des textes de loi sénégalais, organisées sous forme de matrice de décision.

L'analogie la plus simple est celle d'un médecin : avant de poser un diagnostic, il pose des questions précises sur les symptômes du patient. En fonction des réponses, il identifie la maladie et prescrit le traitement adapté. Le moteur fonctionne de la même façon avec les risques juridiques.

### Construction de la base de règles

Avant tout développement, une recherche juridique approfondie a été menée sur le cadre légal sénégalais du numérique. Pour chacun des **9 domaines juridiques** identifiés, 5 informations ont été encodées :

- Les **risques concrets** auxquels la plateforme est exposée
- Les **textes de loi applicables** avec leurs articles précis
- Les **recommandations** de mise en conformité
- Les **sanctions** encourues en cas de non-respect
- L'**autorité compétente** pour contrôler et sanctionner

### Mécanisme de sélection

Lorsqu'un utilisateur soumet le formulaire, le moteur :

1. **Sélectionne la règle de base** correspondant au domaine juridique choisi (ex : protection des données personnelles → règle `donnees_personnelles`)
2. **Applique les modificateurs** selon 3 paramètres contextuels :
   - **Type de plateforme** : une fintech est soumise à des obligations BCEAO renforcées ; une plateforme de santé doit obtenir une autorisation spéciale CDP pour les données sensibles (art. 40 Loi 2008-12)
   - **Contexte géographique** : une opération internationale déclenche l'ajout des textes CEDEAO (Acte A/SA.3/01/2010) et une recommandation de conformité RGPD
   - **Aspects techniques cochés** : la présence de mineurs élève automatiquement le risque au niveau maximal (Code pénal art. 431-35 à 431-40)
3. **Retourne un rapport enrichi** combinant la règle de base et tous les modificateurs applicables

### Exemple concret

Pour une **marketplace** analysant la **protection des données**, opérant au **Sénégal**, avec les aspects **traitement de données** et **accès par des mineurs** cochés :

- Règle de base → risque élevé, Loi 2008-12, sanctions art. 30-31
- Modificateur marketplace → pas de changement (règle de base suffisante)
- Modificateur mineurs → risque maintenu élevé + ajout Code pénal art. 431-35 à 431-40 + obligation de consentement parental (art. 40 Loi 2008-12)
- Résultat → rapport fusionnant les deux niveaux d'analyse

### Avantages de cette approche

| Critère | Système expert (notre approche) | IA générative |
|---|---|---|
| Fiabilité des références légales | Garantie (rédigées manuellement) | Non garantie (hallucinations possibles) |
| Transparence | Totale (règles auditables) | Boîte noire |
| Connaissance du droit sénégalais | Spécialisée | Limitée (données majoritairement occidentales) |
| Coût de fonctionnement | Nul (pas d'API externe) | Variable selon usage |
| Explicabilité juridique | Oui (article par article) | Non |

---

## Sources et références juridiques

### Textes de loi utilisés

| Texte | Domaine |
|---|---|
| Loi n°2008-08 du 25 janvier 2008 | Transactions électroniques |
| Loi n°2008-09 du 25 janvier 2008 | Droit d'auteur et droits voisins |
| Loi n°2008-11 du 25 janvier 2008 | Cybercriminalité (codifiée dans le Code pénal) |
| Loi n°2008-12 du 25 janvier 2008 | Protection des données personnelles |
| Loi n°2016-29 du 08 novembre 2016 | Code des communications électroniques |
| Loi n°2017-27 du 13 juillet 2017 | Code de la presse |
| Loi n°83-01 du 2 mars 1983 | Code de la consommation |
| Loi n°2004-09 du 6 février 2004 | Lutte contre le blanchiment de capitaux |
| Loi n°2015-02 du 13 janvier 2015 | Protection des droits des personnes vulnérables |
| Décret n°2008-721 du 30 juin 2008 | Application de la Loi 2008-12 |
| Instructions BCEAO n°008/2002, 03/2012, 01/2018 | Monnaie électronique et paiement mobile |
| Acte CEDEAO A/SA.3/01/2010 | Protection des données dans l'espace CEDEAO |
| COCC (UEMOA) art. 92-99 | Clauses abusives dans les contrats |
| Accord de Bangui / OAPI (révisé 1999) | Propriété intellectuelle en Afrique |
| Convention CIDE (ratifiée le 31/07/1990) | Droits de l'enfant |
| CGI Sénégal + Directive UEMOA 02/2011 | Fiscalité numérique (TVA 18%) |
| Acte Uniforme OHADA n°04/2017 | Contrats commerciaux transfrontaliers |

### Organismes de référence

| Organisation | Site officiel |
|---|---|
| DRI — Direction des Réseaux et de l'Informatique | https://www.dri.gouv.sn |
| CDP — Commission de Protection des Données | https://www.cdp.sn |
| CENTIF — Cellule de Traitement des Informations Financières | https://www.centif.sn |
| CTIC Dakar — Centre de ressources Tech & Innovation | https://cticdakar.com |
| WIPO Lex — Base mondiale de législations | https://www.wipo.int/wipolex |

---

## Avertissement légal

Les analyses produites par cet outil sont fournies à titre informatif dans un cadre académique. Elles ne constituent pas un avis juridique professionnel et ne sauraient engager la responsabilité de leurs auteurs. Consultez un avocat qualifié pour toute décision engageant votre responsabilité juridique.

---

## Auteurs

Projet réalisé dans le cadre de l'examen de L3 Génie Logiciel — ESITEC · Groupe Supdeco Dakar · 2026
