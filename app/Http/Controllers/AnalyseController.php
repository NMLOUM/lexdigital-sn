<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analyse;
use App\Http\Requests\AnalyseRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyseController extends Controller
{
    private array $categorieLabels = [
        'donnees_personnelles'     => 'Protection des données personnelles',
        'contrat_electronique'     => 'Contrat électronique',
        'paiement'                 => 'Paiement électronique',
        'cybersecurite'            => 'Cybersécurité',
        'contenu_illicite'         => 'Contenu illicite',
        'consommateur'             => 'Droits du consommateur',
        'propriete_intellectuelle' => 'Propriété intellectuelle',
        'fiscalite'                => 'Fiscalité numérique',
        'mineurs'                  => 'Protection des mineurs',
    ];

    private array $typePlateformeLabels = [
        'ecommerce'     => 'E-commerce / Vente en ligne',
        'reseau_social' => 'Réseau social / Communauté',
        'fintech'       => 'Fintech / Paiement mobile',
        'marketplace'   => 'Marketplace / Place de marché',
        'services'      => 'Services numériques (SaaS)',
        'media'         => 'Média / Presse en ligne',
        'sante'         => 'Santé numérique',
        'education'     => 'Éducation / EdTech',
    ];

    private array $contexteGeoLabels = [
        'senegal'       => 'Sénégal uniquement',
        'cedeao'        => 'Zone CEDEAO',
        'international' => 'International (hors CEDEAO)',
    ];

    public function index()
    {
        return view('analyse');
    }

    public function analyser(AnalyseRequest $request)
    {
        $aspects  = $request->aspects ?? [];
        $resultat = $this->analyserJuridiquement(
            $request->categorie_probleme,
            $request->type_plateforme,
            $request->contexte_geo,
            $aspects
        );

        $analyse = Analyse::create([
            'user_id'            => auth()->id(),
            'situation'          => $request->situation,
            'type_plateforme'    => $request->type_plateforme,
            'categorie_probleme' => $request->categorie_probleme,
            'contexte_geo'       => $request->contexte_geo,
            'aspects'            => $aspects,
            'categorie'          => $this->categorieLabels[$request->categorie_probleme],
            'niveau_risque'      => $resultat['niveau_risque'],
            'nature_probleme'    => $resultat['nature_probleme'],
            'risques'            => $resultat['risques'],
            'textes'             => $resultat['textes'],
            'recommandations'    => $resultat['recommandations'],
            'sanctions'          => $resultat['sanctions'],
            'autorite'           => $resultat['autorite'],
        ]);

        return view('resultat', compact('analyse'));
    }

    public function show($id)
    {
        $analyse = Analyse::where('user_id', auth()->id())->findOrFail($id);
        return view('resultat', compact('analyse'));
    }

    public function dashboard(Request $request)
    {
        $query = Analyse::where('user_id', auth()->id())->latest();

        if ($request->filled('niveau_risque')
            && in_array($request->niveau_risque, ['high', 'medium', 'low'])) {
            $query->where('niveau_risque', $request->niveau_risque);
        }

        $analyses = $query->paginate(10)->withQueryString();

        $userAnalyses = Analyse::where('user_id', auth()->id());
        $stats = [
            'total'  => (clone $userAnalyses)->count(),
            'high'   => (clone $userAnalyses)->where('niveau_risque', 'high')->count(),
            'medium' => (clone $userAnalyses)->where('niveau_risque', 'medium')->count(),
            'low'    => (clone $userAnalyses)->where('niveau_risque', 'low')->count(),
        ];

        return view('dashboard', compact('analyses', 'stats'));
    }

    public function exportPdf($id)
    {
        $analyse = Analyse::where('user_id', auth()->id())->findOrFail($id);
        $pdf = Pdf::loadView('pdf.rapport', compact('analyse'))->setPaper('a4', 'portrait');
        return $pdf->download('rapport-juridique-lexdigital-' . $id . '.pdf');
    }

    // -------------------------------------------------------------------------
    // Moteur d'analyse juridique — matrice de règles fondées sur le droit
    // sénégalais : Loi 2008-08, 2008-11, 2008-12, 2016-29, COCC,
    // Instruction BCEAO 008/2002, Acte CEDEAO A/SA.3/01/2010.
    // -------------------------------------------------------------------------
    private function analyserJuridiquement(
        string $categorie,
        string $typePlateforme,
        string $contexteGeo,
        array  $aspects
    ): array {
        $regles = [

            'donnees_personnelles' => [
                'niveau_risque'   => 'high',
                'nature_probleme' => 'Administratif / Pénal',
                'risques'         => "Collecte ou traitement illicite de données personnelles. Absence de consentement éclairé des utilisateurs. Défaut de déclaration du traitement auprès de la CDP avant mise en œuvre. Transfert non autorisé de données à caractère personnel vers l'étranger. Conservation excessive des données au-delà des durées légales autorisées.",
                'textes'          => "Loi n° 2008-12 du 25 janvier 2008 relative à la Protection des Données à Caractère Personnel (art. 4(4) — définitions, art. 7-1 — consentement, art. 18 — déclaration CDP, art. 32 + art. 36 — droits des personnes, art. 40 — données sensibles, art. 16(6) + art. 54-57 — transferts internationaux, art. 30-31 — sanctions). Décret n° 2008-721 du 30 juin 2008 portant application de la Loi 2008-12.",
                'recommandations' => "Déclarer tout traitement de données à la CDP avant toute mise en œuvre (formulaire de déclaration en ligne). Obtenir un consentement explicite, libre et éclairé de chaque utilisateur. Publier une politique de confidentialité détaillée et accessible depuis toutes les pages. Chiffrer les bases de données et restreindre les accès internes. Désigner un Correspondant Informatique et Libertés (CIL). Appliquer le principe de minimisation : ne collecter que les données strictement nécessaires.",
                'sanctions'       => "Amende jusqu'à 100 000 000 FCFA (art. 30-31 Loi 2008-12). Retrait d'autorisation de traitement par la CDP. Injonction de cessation immédiate du traitement illicite.",
                'autorite'        => "Commission des Données Personnelles (CDP) — Dakar. Contact : www.cdp.sn",
            ],

            'contrat_electronique' => [
                'niveau_risque'   => 'medium',
                'nature_probleme' => 'Civil / Commercial',
                'risques'         => "Nullité du contrat électronique pour défaut de forme valide ou d'information précontractuelle. Absence d'accusé de réception électronique obligatoire. Clause abusive non opposable au cocontractant. Litige sur la formation, la preuve et l'exécution du contrat à distance. Non-respect du délai de rétractation légal.",
                'textes'          => "Loi n° 2008-08 du 25 janvier 2008 sur les Transactions Électroniques (art. 3(2-3) — champ d'application, art. 5 + art. 10 — obligations d'information, art. 24 + art. 25 — formation du contrat en ligne, art. 37 — preuve et conservation). COCC du Sénégal — Code des Obligations Civiles et Commerciales (art. 44 et s. sur la formation du contrat). Loi n° 2016-29 du 08 novembre 2016 portant Code des Communications Électroniques du Sénégal.",
                'recommandations' => "Afficher toutes les informations précontractuelles obligatoires avant validation de commande (identité du vendeur, prix TTC, délais). Intégrer une étape de confirmation explicite (double-clic de validation). Envoyer systématiquement un accusé de réception électronique horodaté. Conserver les preuves de consentement et d'acceptation pendant au moins 5 ans. Permettre un délai de rétractation de 7 jours minimum. Rédiger les CGV en français, de manière claire et accessible.",
                'sanctions'       => "Nullité du contrat. Dommages-intérêts pour préjudice subi par le cocontractant. Injonction judiciaire de mise en conformité. Amende administrative possible.",
                'autorite'        => "Tribunal de Commerce de Dakar. Direction Générale du Commerce Intérieur (DGCI) — Ministère du Commerce.",
            ],

            'paiement' => [
                'niveau_risque'   => 'high',
                'nature_probleme' => 'Administratif / Pénal',
                'risques'         => "Exercice d'une activité de paiement ou de transfert de fonds électronique sans agrément BCEAO. Non-respect des obligations de lutte contre le blanchiment de capitaux et le financement du terrorisme (LCB/FT). Absence de dispositif KYC (Know Your Customer) pour l'identification des clients. Fraude aux moyens de paiement électronique. Défaut de ségrégation des fonds clients.",
                'textes'          => "Instruction BCEAO sur la monnaie électronique (n°008/2002/SP, révisée par n°03/2012/CM/UEMOA puis n°01/2018/CM/UEMOA) — agrément et conditions d'exercice des activités de paiement électronique. Loi n° 2008-08 sur les Transactions Électroniques (art. 24 + art. 25 — sécurité des paiements en ligne). Acte additionnel CEDEAO A/SA.3/01/2010 sur la protection des données dans l'espace CEDEAO. Loi n° 2004-09 du 6 février 2004 relative à la lutte contre le blanchiment de capitaux.",
                'recommandations' => "Obtenir un agrément d'Établissement de Monnaie Électronique (EME) auprès de la BCEAO avant tout lancement. Mettre en place un dispositif KYC rigoureux : identification, vérification et surveillance des clients. Déclarer les opérations suspectes à la CENTIF dans les délais légaux. Sécuriser les flux de paiement selon les normes PCI-DSS (niveau approprié). Mettre en place la ségrégation des fonds clients sur des comptes dédiés.",
                'sanctions'       => "Retrait d'agrément ou interdiction d'exercer par la BCEAO. Amende de 5 000 000 à 50 000 000 FCFA. Gel des avoirs. Responsabilité personnelle des dirigeants devant les juridictions compétentes.",
                'autorite'        => "BCEAO — Banque Centrale des États de l'Afrique de l'Ouest. CENTIF — Cellule Nationale de Traitement des Informations Financières.",
            ],

            'cybersecurite' => [
                'niveau_risque'   => 'high',
                'nature_probleme' => 'Pénal',
                'risques'         => "Accès frauduleux à un système informatique par un tiers (hacking). Atteinte à l'intégrité, la disponibilité ou la confidentialité des données. Captation illicite de données à caractère personnel par exploitation d'une faille. Défaut de notification d'un incident de sécurité aux autorités compétentes. Responsabilité engagée de l'opérateur en cas de faille de sécurité avérée non corrigée.",
                'textes'          => "Loi n° 2008-11 du 25 janvier 2008 portant sur la Cybercriminalité, codifiée dans le Code pénal sénégalais (art. 431-10 à 431-12 — accès non autorisé et maintien frauduleux, art. 431-13 à 431-16 — atteinte à l'intégrité, interception et sabotage, art. 431-35 à 431-40 — atteinte aux données personnelles). Loi n° 2008-12 sur la Protection des Données (obligation de sécurité du responsable de traitement). Loi n° 2016-29 portant Code des Communications Électroniques.",
                'recommandations' => "Élaborer et tester régulièrement un plan de réponse aux incidents de cybersécurité (CSIRT interne ou externe). Conduire des audits de sécurité et tests de pénétration (pentests) au moins une fois par an. Chiffrer toutes les données sensibles au repos et en transit (TLS 1.2+ minimum). Notifier la CDP et les utilisateurs concernés dans les 72 heures suivant toute violation de données. Appliquer le principe du moindre privilège dans la gestion des accès systèmes.",
                'sanctions'       => "Emprisonnement de 6 mois à 10 ans selon la gravité (art. 431-10 à 431-16 Code pénal). Amende de 1 000 000 à 25 000 000 FCFA. Responsabilité civile pour réparation des préjudices causés aux victimes. Interdiction d'exercer.",
                'autorite'        => "Police cybernétique du Sénégal (OPJ spécialisés — Direction de la Police Judiciaire). ADIE — Agence de l'Informatique de l'État. CDP pour la dimension protection des données.",
            ],

            'contenu_illicite' => [
                'niveau_risque'   => 'high',
                'nature_probleme' => 'Pénal',
                'risques'         => "Publication ou hébergement de contenu diffamatoire, injurieux ou portant atteinte à l'honneur. Diffusion de discours de haine, propagande terroriste ou apologie de crime en ligne. Atteinte à la vie privée par publication non consentie d'images ou de données personnelles. Contenu à caractère pornographique impliquant des mineurs. Responsabilité de l'hébergeur en cas de non-retrait rapide de contenu signalé.",
                'textes'          => "Loi n° 2008-11 sur la Cybercriminalité, codifiée dans le Code pénal sénégalais (art. 431-25 à 431-28 — diffamation et injure en ligne, art. 431-35 à 431-40 — atteinte à la vie privée et protection des mineurs). Code de la Presse du Sénégal — Loi n° 2017-27 du 13 juillet 2017 (art. 98 à 110 — délits de presse en ligne). COCC art. 118 et s. — responsabilité civile délictuelle.",
                'recommandations' => "Mettre en place une procédure de modération active des contenus (automatisée et humaine). Créer un mécanisme de signalement accessible depuis toutes les pages (bouton visible). Retirer immédiatement tout contenu illicite signalé dans les 24h (procédure notice-and-takedown). Conserver les logs de modération et de signalement pendant 12 mois minimum. Rédiger et afficher des CGU claires, une charte de modération et les sanctions appliquées aux utilisateurs.",
                'sanctions'       => "Emprisonnement de 1 à 7 ans pour contenus terroristes ou haineux (art. 431-25 à 431-28 Code pénal). Amende de 5 000 000 à 20 000 000 FCFA. Fermeture judiciaire ou administrative de la plateforme. Blocage d'accès par l'ARTP sur réquisition judiciaire.",
                'autorite'        => "Parquet — Pôle spécialisé en cybercriminalité. ARTP — Autorité de Régulation des Télécommunications et des Postes. CORED — Conseil pour l'Observation des Règles d'Éthique et de Déontologie (presse en ligne).",
            ],

            'consommateur' => [
                'niveau_risque'   => 'medium',
                'nature_probleme' => 'Civil / Administratif',
                'risques'         => "Pratiques commerciales déloyales ou trompeuses sur la plateforme en ligne. Publicité mensongère sur les caractéristiques, la disponibilité ou le prix d'un produit. Clauses abusives dans les conditions générales de vente non opposables au consommateur. Non-respect du droit de rétractation légal de 7 jours minimum. Absence d'informations obligatoires sur l'identité et les coordonnées du vendeur professionnel.",
                'textes'          => "Loi n° 2008-08 sur les Transactions Électroniques (art. 5 + art. 10 — obligations d'information précontractuelle, art. 24 + art. 25 — formation du contrat en ligne). Loi n° 83-01 du 2 mars 1983 portant Code de la Consommation du Sénégal. COCC (Code des Obligations Civiles et Commerciales de l'UEMOA) art. 92-99 — clauses abusives créant un déséquilibre significatif réputées non écrites.",
                'recommandations' => "Afficher de manière lisible et visible : prix TTC, frais de livraison, délais, conditions de retour et de remboursement. Mentionner l'identité complète du vendeur professionnel (NINEA, adresse physique, contact). Éliminer toute clause créant un déséquilibre significatif au détriment du consommateur. Tenir un registre des réclamations et répondre dans un délai de 48h. Prévoir un mécanisme de remboursement transparent et sans obstacle.",
                'sanctions'       => "Amende administrative jusqu'à 5 000 000 FCFA. Injonction judiciaire de mise en conformité. Dommages-intérêts au consommateur lésé devant le tribunal d'instance compétent.",
                'autorite'        => "Direction Générale du Commerce Intérieur (DGCI) — Ministère du Commerce. Tribunal d'instance compétent pour le contentieux consommateur.",
            ],

            'propriete_intellectuelle' => [
                'niveau_risque'   => 'medium',
                'nature_probleme' => 'Civil / Pénal',
                'risques'         => "Contrefaçon de logiciel, d'œuvre numérique ou de base de données protégée. Utilisation non autorisée de marque, logo, nom commercial ou dénomination déposée à l'OAPI. Violation de droits d'auteur sur des contenus téléversés ou partagés par des utilisateurs tiers. Extraction illicite de bases de données par scraping abusif. Utilisation de composants open-source sous licences incompatibles avec l'usage commercial.",
                'textes'          => "Loi n° 2008-09 du 25 janvier 2008 sur le Droit d'Auteur et les Droits Voisins au Sénégal (art. 10 — œuvres protégées, art. 41 — droits patrimoniaux, art. 59 — logiciels, art. 83 — sanctions). Accord de Bangui de 1977, révisé en 1999 — OAPI (Organisation Africaine de la Propriété Intellectuelle). Convention de Berne pour la protection des œuvres littéraires et artistiques.",
                'recommandations' => "Auditer les licences de tous les logiciels, frameworks et contenus tiers utilisés dans la plateforme. Mettre en place une procédure de retrait sur notification (équivalent DMCA) pour les contenus utilisateurs. Déposer les marques, logos et œuvres originales auprès de l'OAPI et du BSDA. Rédiger dans les CGU des clauses précises sur la propriété des contenus produits par les utilisateurs. Former les équipes techniques et commerciales aux bases du droit de la propriété intellectuelle.",
                'sanctions'       => "Saisie-contrefaçon judiciaire des œuvres ou produits illicites. Dommages-intérêts proportionnels au préjudice subi. Emprisonnement de 3 mois à 3 ans. Amende jusqu'à 30 000 000 FCFA.",
                'autorite'        => "BSDA — Bureau Sénégalais du Droit d'Auteur. OAPI — Organisation Africaine de la Propriété Intellectuelle. Tribunal correctionnel pour poursuites pénales.",
            ],

            'fiscalite' => [
                'niveau_risque'   => 'medium',
                'nature_probleme' => 'Administratif / Pénal',
                'risques'         => "Exercice d'activité numérique commerciale sans immatriculation fiscale (NINEA). Non-collecte ou non-reversement de la TVA sur les services et ventes numériques. Évasion fiscale via des montages transfrontaliers abusifs. Défaut de facturation électronique conforme aux exigences fiscales. Sous-déclaration ou omission de revenus issus de l'économie numérique.",
                'textes'          => "Code Général des Impôts du Sénégal (CGI) — art. 356 et s. sur la TVA (taux de 18%). Loi de Finances annuelle du Sénégal (dispositions numériques). Directive n° 02/2011/CM/UEMOA relative à l'harmonisation de la TVA dans l'UEMOA.",
                'recommandations' => "Immatriculer la plateforme auprès de la DGID et obtenir le Numéro d'Identification National des Entreprises et Associations (NINEA). Collecter et reverser la TVA au taux applicable de 18% sur toutes les ventes et prestations de services numériques. Mettre en place un système de facturation électronique conforme et archivée pendant 10 ans. Déclarer mensuellement via e-tax DGI. Consulter un expert-comptable local agréé pour optimiser la structure fiscale.",
                'sanctions'       => "Redressement fiscal avec pénalités de 25% à 100% des sommes dues. Amendes et intérêts de retard. Solidarité entre dirigeants et entreprise pour le paiement des dettes fiscales. Poursuites pénales pour fraude fiscale aggravée (emprisonnement jusqu'à 5 ans).",
                'autorite'        => "DGID — Direction Générale des Impôts et Domaines. Centre des Services Fiscaux (CSF) de Dakar Plateau ou compétent selon domiciliation.",
            ],

            'mineurs' => [
                'niveau_risque'   => 'high',
                'nature_probleme' => 'Pénal / Administratif',
                'risques'         => "Collecte de données personnelles de mineurs (moins de 15 ans) sans consentement parental explicitement recueilli. Exposition de mineurs à des contenus violents, pornographiques ou inadaptés à leur âge. Absence de mécanisme fiable de vérification de l'âge des utilisateurs à l'inscription. Profilage commercial ou exploitation des données de mineurs à des fins publicitaires. Absence de contrôle parental effectif proposé par la plateforme.",
                'textes'          => "Loi n° 2008-12 sur la Protection des Données Personnelles — le traitement de données de mineurs requiert le consentement parental (principe général art. 4(4) et art. 40). Loi n° 2008-11 sur la Cybercriminalité, codifiée dans le Code pénal sénégalais (art. 431-35 à 431-40 — protection spécifique des mineurs en ligne, peines aggravées). Convention Internationale des Droits de l'Enfant (CIDE) ratifiée par le Sénégal le 31 juillet 1990. Loi n° 2015-02 du 13 janvier 2015 relative à l'égalité sociale et à la protection des droits des personnes vulnérables.",
                'recommandations' => "Déployer un système de vérification d'âge robuste et non contournable dès l'inscription. Recueillir et conserver la preuve du consentement parental pour tout utilisateur de moins de 15 ans. Mettre en place un filtrage actif des contenus sensibles et une modération renforcée. Proposer un mode 'junior' avec des fonctionnalités adaptées et des contenus vérifiés. Nommer un référent dédié à la protection de l'enfance au sein de la direction de la plateforme.",
                'sanctions'       => "Peine pénale doublée lorsque la victime est mineure (aggravante légale). Amende majorée. Fermeture administrative immédiate possible en cas d'urgence. Inscription au fichier judiciaire national des auteurs d'infractions sexuelles si contenus pédo-criminels. Responsabilité personnelle engagée des dirigeants de la plateforme.",
                'autorite'        => "CDP — Commission des Données Personnelles. Direction de la Protection des Droits de l'Enfant (Ministère de la Femme, de la Famille et de la Protection des Enfants). Justice des mineurs — Tribunal pour enfants. Brigade de Protection des Mineurs (BPM) — Police nationale.",
            ],
        ];

        // Règle de base
        $result = $regles[$categorie] ?? $regles['contrat_electronique'];

        // --- Modificateurs selon type_plateforme ---
        if ($typePlateforme === 'fintech' && in_array($categorie, ['paiement', 'cybersecurite'])) {
            $result['niveau_risque'] = 'high';
            $result['textes'] .= " Instruction BCEAO sur la monnaie électronique (n°008/2002/SP et révisions n°03/2012/CM/UEMOA, n°01/2018/CM/UEMOA) — obligations renforcées pour les plateformes fintech.";
        }
        if ($typePlateforme === 'sante' && $categorie === 'donnees_personnelles') {
            $result['niveau_risque'] = 'high';
            $result['risques'] .= " Données de santé : traitement soumis à autorisation spéciale de la CDP (catégorie sensible, art. 40 Loi 2008-12) — régime d'autorisation préalable obligatoire.";
        }
        if ($typePlateforme === 'media' && $categorie === 'contenu_illicite') {
            $result['textes'] .= " Code de la Presse du Sénégal (Loi n° 2017-27) — obligations renforcées de déontologie pour les médias en ligne.";
        }
        if ($typePlateforme === 'education' && $categorie === 'mineurs') {
            $result['niveau_risque'] = 'high';
            $result['recommandations'] .= " Plateforme éducative : mise en œuvre obligatoire d'un espace parent sécurisé et de rapports d'activité accessibles aux tuteurs légaux.";
        }

        // --- Modificateurs selon contexte géographique ---
        if ($contexteGeo === 'international') {
            $result['textes'] .= " Acte additionnel CEDEAO A/SA.3/01/2010 du 16 février 2010 sur les transactions électroniques dans l'espace CEDEAO.";
            $result['recommandations'] .= " Vérifier la conformité avec la réglementation des pays cibles (notamment le RGPD si des utilisateurs de l'Union Européenne sont visés).";
            if ($result['niveau_risque'] === 'low') {
                $result['niveau_risque'] = 'medium';
            }
        } elseif ($contexteGeo === 'cedeao') {
            $result['textes'] .= " Acte additionnel CEDEAO A/SA.3/01/2010 du 16 février 2010 sur les transactions électroniques.";
        }

        // --- Modificateurs selon aspects cochés ---
        if (in_array('acces_mineurs', $aspects) && $categorie !== 'mineurs') {
            $result['niveau_risque'] = 'high';
            $result['risques'] .= " Présence confirmée de mineurs : obligations renforcées applicables — Code pénal art. 431-35 à 431-40 (Loi 2008-11) et art. 40 Loi 2008-12 pour les données sensibles.";
        }
        if (in_array('paiement_electronique', $aspects) && $categorie !== 'paiement') {
            $result['risques'] .= " Paiement électronique impliqué : vérifier la conformité à l'Instruction BCEAO n° 008/2002/SP.";
            $result['textes'] .= " Instruction BCEAO sur la monnaie électronique (n°008/2002/SP, révisée par n°03/2012/CM/UEMOA et n°01/2018/CM/UEMOA).";
        }
        if (in_array('operations_transfrontalieres', $aspects) && $contexteGeo === 'senegal') {
            $result['textes'] .= " Acte CEDEAO A/SA.3/01/2010 applicable pour les opérations transfrontalières.";
        }
        if (in_array('traitement_donnees', $aspects) && $categorie !== 'donnees_personnelles') {
            $result['risques'] .= " Traitement de données personnelles détecté : déclaration CDP obligatoire (Loi 2008-12).";
            $result['textes'] .= " Loi n° 2008-12 sur la Protection des Données Personnelles (obligations de déclaration).";
        }

        return $result;
    }
}
