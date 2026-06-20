@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

{{-- HERO --}}
<section style="background:var(--ink);padding:5rem 2rem 4rem;border-bottom:1px solid rgba(200,153,58,.15);">
    <div style="max-width:820px;margin:0 auto;">

        <div style="font-family:'IBM Plex Mono',monospace;font-size:.68rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);margin-bottom:1.25rem;">
            Droit du numérique · Sénégal
        </div>

        <h1 style="font-family:'Playfair Display',serif;font-size:2.75rem;line-height:1.15;color:#fff;margin-bottom:1.25rem;max-width:680px;">
            Évaluez les risques juridiques de votre plateforme numérique
        </h1>

        <p style="font-size:1rem;color:rgba(255,255,255,.5);line-height:1.7;max-width:560px;margin-bottom:2.25rem;">
            LexDigital SN analyse la conformité de votre plateforme au regard du cadre juridique sénégalais et vous fournit des recommandations personnalisées fondées sur les textes en vigueur.
        </p>

        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
            @auth
                <a href="{{ route('home') }}" class="btn btn-primary" style="width:auto;padding:.85rem 2rem;font-size:.95rem;">
                    Lancer une analyse
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.6);">
                    Mes analyses
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary" style="width:auto;padding:.85rem 2rem;font-size:.95rem;">
                    Commencer gratuitement
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary" style="border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.6);">
                    Se connecter
                </a>
            @endauth
        </div>

    </div>
</section>

{{-- BANDE CHIFFRES --}}
<section style="background:var(--surface);border-bottom:1px solid var(--rule);">
    <div style="max-width:820px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--rule);">
        @foreach([['9','domaines juridiques analysés'],['17','textes de loi intégrés'],['3','niveaux de risque évalués']] as [$num,$label])
        <div style="background:var(--surface);padding:2rem 1.75rem;">
            <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--accent);line-height:1;margin-bottom:.35rem;">{{ $num }}</div>
            <div style="font-size:.82rem;color:var(--ink-muted);">{{ $label }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- DOMAINES COUVERTS --}}
<section style="padding:4rem 2rem;background:var(--paper);">
    <div style="max-width:820px;margin:0 auto;">

        <div style="font-family:'IBM Plex Mono',monospace;font-size:.67rem;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:2rem;">
            Domaines juridiques couverts
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem;">
            @php $domaines = [
                ['Protection des données','Loi n°2008-12'],
                ['Contrats électroniques','Loi n°2008-08'],
                ['Paiement mobile','BCEAO 008/2002'],
                ['Cybersécurité','Loi n°2008-11'],
                ['Contenus illicites','Code pénal + Presse'],
                ['Consommateurs','Loi n°83-01'],
                ['Propriété intellectuelle','OAPI / Bangui'],
                ['Fiscalité numérique','CGI · TVA 18%'],
                ['Protection des mineurs','Code pénal art. 431-35'],
            ]; @endphp
            @foreach($domaines as [$name,$ref])
            <div style="background:var(--surface);border:1px solid var(--rule);border-radius:var(--radius);padding:.75rem 1rem;">
                <div style="font-size:.85rem;font-weight:500;color:var(--ink);margin-bottom:.2rem;">{{ $name }}</div>
                <div style="font-size:.7rem;font-family:'IBM Plex Mono',monospace;color:var(--ink-muted);">{{ $ref }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- COMMENT ÇA MARCHE --}}
<section style="padding:4rem 2rem;background:var(--surface);border-top:1px solid var(--rule);border-bottom:1px solid var(--rule);">
    <div style="max-width:820px;margin:0 auto;">

        <div style="font-family:'IBM Plex Mono',monospace;font-size:.67rem;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:2rem;">
            Comment ça fonctionne
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;">
            @foreach([
                ['01','Décrivez votre situation','Renseignez le type de votre plateforme, le domaine juridique concerné et décrivez votre situation en quelques lignes.'],
                ['02','Le moteur analyse','Notre système expert applique les règles du droit sénégalais du numérique pour évaluer vos risques selon votre contexte précis.'],
                ['03','Obtenez votre rapport','Vous recevez instantanément les risques identifiés, les textes applicables, les recommandations et les sanctions encourues.'],
            ] as [$num,$title,$desc])
            <div>
                <div style="font-family:'IBM Plex Mono',monospace;font-size:.68rem;font-weight:600;color:var(--gold);letter-spacing:.1em;margin-bottom:.75rem;">{{ $num }}</div>
                <div style="font-size:.95rem;font-weight:600;color:var(--ink);margin-bottom:.5rem;">{{ $title }}</div>
                <div style="font-size:.85rem;color:var(--ink-muted);line-height:1.65;">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA BAS DE PAGE --}}
<section style="padding:4rem 2rem;background:var(--paper);">
    <div style="max-width:820px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:2rem;flex-wrap:wrap;">
        <div>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--ink);margin-bottom:.4rem;">
                Prêt à évaluer votre conformité ?
            </h2>
            <p style="font-size:.88rem;color:var(--ink-muted);">
                Créez votre compte gratuitement et lancez votre première analyse en moins de 2 minutes.
            </p>
        </div>
        @auth
            <a href="{{ route('home') }}" class="btn btn-primary" style="width:auto;padding:.85rem 2rem;white-space:nowrap;">
                Nouvelle analyse
            </a>
        @else
            <a href="{{ route('register') }}" class="btn btn-primary" style="width:auto;padding:.85rem 2rem;white-space:nowrap;">
                Créer un compte
            </a>
        @endauth
    </div>
</section>

{{-- DISCLAIMER --}}
<div style="background:var(--surface);border-top:1px solid var(--rule);padding:1.25rem 2rem;">
    <div style="max-width:820px;margin:0 auto;font-size:.73rem;color:var(--ink-muted);line-height:1.5;">
        Les analyses produites par cet outil sont fournies à titre informatif dans un cadre académique et ne constituent pas un avis juridique professionnel. Consultez un avocat qualifié pour toute décision engageant votre responsabilité.
        <br>Projet académique — ESITEC L3 Génie Logiciel · Groupe Supdeco Dakar · 2026
    </div>
</div>

@endsection
