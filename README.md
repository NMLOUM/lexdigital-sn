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

## Avertissement légal

Les analyses produites par cet outil sont fournies à titre informatif dans un cadre académique. Elles ne constituent pas un avis juridique professionnel et ne sauraient engager la responsabilité de leurs auteurs. Consultez un avocat qualifié pour toute décision engageant votre responsabilité juridique.

---

## Auteurs

Projet réalisé dans le cadre de l'examen de L3 Génie Logiciel — ESITEC · Groupe Supdeco Dakar · 2026
