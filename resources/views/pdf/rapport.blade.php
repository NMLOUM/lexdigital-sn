<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Juridique LexDigital SN — #{{ $analyse->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #2c2c2c;
            background: #ffffff;
            padding: 38px;
        }

        .header { padding: 22px 28px; border-radius: 7px; margin-bottom: 22px; }
        .header-high    { background: #dc3545; color: #fff; }
        .header-medium  { background: #ffc107; color: #212529; }
        .header-low     { background: #198754; color: #fff; }
        .header-default { background: #198754; color: #fff; }

        .header h1 { font-size: 19px; font-weight: bold; margin-bottom: 4px; }
        .header p  { font-size: 10px; opacity: .85; }

        .risque-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            background: rgba(255,255,255,.25);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .meta {
            font-size: 9px;
            color: #666;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .summary-table td {
            width: 33.33%;
            padding: 8px 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 10px;
            vertical-align: top;
        }
        .summary-label { font-size: 8px; text-transform: uppercase; color: #888; letter-spacing: .4px; margin-bottom: 3px; }

        .section { border-left: 4px solid #ccc; padding: 10px 14px; margin-bottom: 11px; border-radius: 0 5px 5px 0; }
        .section-situation      { border-color: #6c757d; background: #f8f9fa; }
        .section-risques        { border-color: #dc3545; background: #fde8ea; }
        .section-textes         { border-color: #ffc107; background: #fff9e6; }
        .section-recommandations{ border-color: #198754; background: #e8f5ee; }
        .section-sanctions      { border-color: #343a40; background: #f1f3f5; }
        .section-autorite       { border-color: #0d6efd; background: #e8f0fe; }

        .section-label {
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #555;
            margin-bottom: 5px;
        }
        .section-value { font-size: 10.5px; line-height: 1.65; }

        .footer {
            margin-top: 32px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            font-size: 8.5px;
            color: #aaa;
            text-align: center;
        }
    </style>
</head>
<body>

@php
    $headerClass = match($analyse->niveau_risque) {
        'high'   => 'header-high',
        'medium' => 'header-medium',
        'low'    => 'header-low',
        default  => 'header-default',
    };
    $niveauLabel = match($analyse->niveau_risque) {
        'high'   => 'Risque élevé',
        'medium' => 'Risque modéré',
        'low'    => 'Risque faible',
        default  => 'Non évalué',
    };
    $typePlateformeLabels = [
        'ecommerce'=>'E-commerce','reseau_social'=>'Réseau social','fintech'=>'Fintech',
        'marketplace'=>'Marketplace','services'=>'Services SaaS','media'=>'Média / Presse',
        'sante'=>'Santé numérique','education'=>'Éducation / EdTech',
    ];
    $contexteGeoLabels = [
        'senegal'=>'Sénégal','cedeao'=>'Zone CEDEAO','international'=>'International',
    ];
@endphp

<div class="header {{ $headerClass }}">
    <h1>LexDigital SN — Rapport d'Analyse Juridique</h1>
    <p>Système d'analyse des risques juridiques des plateformes numériques au Sénégal</p>
    <span class="risque-badge">{{ $niveauLabel }}</span>
</div>

<div class="meta">
    Rapport n°&nbsp;{{ $analyse->id }}
    &bull; Généré le {{ now()->format('d/m/Y à H:i') }}
    @if($analyse->contexte_geo)
        &bull; Contexte : {{ $contexteGeoLabels[$analyse->contexte_geo] ?? $analyse->contexte_geo }}
    @endif
</div>

<table class="summary-table">
    <tr>
        <td>
            <div class="summary-label">Type de plateforme</div>
            {{ $typePlateformeLabels[$analyse->type_plateforme] ?? ($analyse->type_plateforme ?? '—') }}
        </td>
        <td>
            <div class="summary-label">Catégorie juridique</div>
            {{ $analyse->categorie }}
        </td>
        <td>
            <div class="summary-label">Nature du problème</div>
            {{ $analyse->nature_probleme ?? '—' }}
        </td>
    </tr>
</table>

<div class="section section-situation">
    <div class="section-label">Situation analysée</div>
    <div class="section-value">{{ $analyse->situation }}</div>
</div>

<div class="section section-risques">
    <div class="section-label">Risques juridiques identifiés</div>
    <div class="section-value">{{ $analyse->risques }}</div>
</div>

<div class="section section-textes">
    <div class="section-label">Textes juridiques applicables</div>
    <div class="section-value">{{ $analyse->textes }}</div>
</div>

<div class="section section-recommandations">
    <div class="section-label">Recommandations</div>
    <div class="section-value">{{ $analyse->recommandations }}</div>
</div>

@if($analyse->sanctions)
<div class="section section-sanctions">
    <div class="section-label">Sanctions encourues</div>
    <div class="section-value">{{ $analyse->sanctions }}</div>
</div>
@endif

@if($analyse->autorite)
<div class="section section-autorite">
    <div class="section-label">Autorité compétente</div>
    <div class="section-value">{{ $analyse->autorite }}</div>
</div>
@endif

<div class="footer">
    LexDigital SN &mdash; Analyse juridique des plateformes numériques &mdash; ESITEC L3 Génie Logiciel &mdash; Droit du Numérique Sénégal
</div>

</body>
</html>
