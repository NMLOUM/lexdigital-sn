@extends('layouts.app')

@section('title', 'Rapport — ' . ucwords(str_replace('_',' ',$analyse->categorie_probleme ?? $analyse->categorie)))

@section('content')
<div style="max-width:820px;margin:0 auto;padding:3rem 1.5rem 2.5rem;">

    {{-- FIL D'ARIANE --}}
    <div style="font-size:.75rem;color:var(--ink-muted);margin-bottom:2rem;display:flex;align-items:center;gap:.5rem;">
        <a href="{{ route('home') }}" style="color:var(--ink-muted);">Nouvelle analyse</a>
        <span style="color:var(--rule);">›</span>
        <a href="{{ route('dashboard') }}" style="color:var(--ink-muted);">Mes analyses</a>
        <span style="color:var(--rule);">›</span>
        <span>Rapport #{{ $analyse->id }}</span>
    </div>

    {{-- EN-TÊTE DU RAPPORT --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1.5rem;flex-wrap:wrap;margin-bottom:2rem;">
        <div>
            <div style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:.5rem;">
                Rapport d'analyse · {{ $analyse->created_at->format('d/m/Y à H:i') }}
            </div>
            <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;line-height:1.2;color:var(--ink);margin-bottom:.35rem;">
                {{ ucwords(str_replace('_',' ',$analyse->categorie_probleme ?? $analyse->categorie)) }}
            </h1>
            <div style="font-size:.83rem;color:var(--ink-muted);">
                {{ ucfirst(str_replace('_',' ',$analyse->type_plateforme ?? '—')) }}
                &thinsp;·&thinsp;
                {{ ucfirst(str_replace('_',' ',$analyse->contexte_geo ?? '—')) }}
            </div>
        </div>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;align-self:flex-start;padding-top:.25rem;">
            <a href="{{ route('analyse.pdf', $analyse->id) }}" class="btn btn-secondary" target="_blank" style="font-size:.8rem;">
                Télécharger PDF
            </a>
            <a href="{{ route('home') }}" class="btn btn-secondary" style="font-size:.8rem;">
                Nouvelle analyse
            </a>
        </div>
    </div>

    {{-- BANDEAU DE NIVEAU DE RISQUE --}}
    @php
        $lvl = $analyse->niveau_risque ?? 'medium';
        $riskConfig = [
            'high'   => ['label' => 'Risque élevé',  'desc' => 'Des mesures correctives immédiates sont requises.'],
            'medium' => ['label' => 'Risque modéré', 'desc' => 'Des mesures de mise en conformité sont recommandées.'],
            'low'    => ['label' => 'Risque faible',  'desc' => 'Situation globalement conforme — surveillance conseillée.'],
        ];
        $rc = $riskConfig[$lvl] ?? $riskConfig['medium'];
    @endphp
    <div class="risk-strip {{ $lvl }}">
        <div class="risk-dot"></div>
        <div>
            <div class="risk-label">{{ $rc['label'] }}</div>
            <div style="font-size:.85rem;color:var(--ink-2);margin-top:.15rem;">
                {{ $rc['desc'] }}
                @if($analyse->nature_probleme)
                    &ensp;—&ensp;<em style="color:var(--ink-muted);">{{ $analyse->nature_probleme }}</em>
                @endif
            </div>
        </div>
    </div>

    {{-- CORPS DU RAPPORT --}}
    <div class="card" style="padding:2rem 2.25rem;">

        {{-- 1. Risques identifiés --}}
        @if($analyse->risques)
        <div class="report-section">
            <div class="report-label">Risques identifiés</div>
            <ul class="list-report danger">
                @foreach(array_filter(array_map('trim', explode("\n", $analyse->risques))) as $line)
                    @if($line)<li>{{ ltrim($line,'-•·* ') }}</li>@endif
                @endforeach
            </ul>
        </div>
        @endif

        {{-- 2. Textes juridiques --}}
        @if($analyse->textes)
        <div class="report-section">
            <div class="report-label">Textes juridiques applicables</div>
            <div style="display:flex;flex-wrap:wrap;gap:.3rem;">
                @foreach(array_filter(array_map('trim', explode("\n", $analyse->textes))) as $t)
                    @if($t)<span class="law-ref">{{ ltrim($t,'-•·* ') }}</span>@endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- 3. Recommandations --}}
        @if($analyse->recommandations)
        <div class="report-section">
            <div class="report-label">Recommandations</div>
            @php $recos = array_values(array_filter(array_map('trim', explode("\n", $analyse->recommandations)))); @endphp
            <ol style="padding-left:0;list-style:none;">
                @foreach($recos as $i => $reco)
                @if($reco)
                <li style="display:flex;gap:1rem;padding:.65rem 0;border-bottom:1px solid var(--rule);font-size:.88rem;line-height:1.6;color:var(--ink-2);">
                    <span style="font-family:'IBM Plex Mono',monospace;font-size:.68rem;font-weight:600;color:var(--accent);min-width:24px;padding-top:3px;flex-shrink:0;">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</span>
                    <span>{{ ltrim($reco,'-•·* ') }}</span>
                </li>
                @endif
                @endforeach
            </ol>
        </div>
        @endif

        {{-- 4. Sanctions --}}
        @if($analyse->sanctions)
        <div class="report-section">
            <div class="report-label">Sanctions encourues</div>
            <ul class="list-report danger">
                @foreach(array_filter(array_map('trim', explode("\n", $analyse->sanctions))) as $s)
                    @if($s)<li>{{ ltrim($s,'-•·* ') }}</li>@endif
                @endforeach
            </ul>
        </div>
        @endif

        {{-- 5. Autorité compétente --}}
        @if($analyse->autorite)
        <div class="report-section" style="margin-bottom:0;">
            <div class="report-label">Autorité compétente</div>
            <div style="font-size:.88rem;line-height:1.7;color:var(--ink-2);background:var(--paper);padding:.85rem 1rem;border-radius:var(--radius);border-left:3px solid var(--rule);">
                {{ $analyse->autorite }}
            </div>
        </div>
        @endif

    </div>

    {{-- SITUATION ANALYSÉE --}}
    <div style="margin-top:1.5rem;padding:1.25rem 1.5rem;border:1px solid var(--rule);border-radius:var(--radius);background:var(--surface);">
        <div style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:.75rem;">
            Situation analysée
        </div>
        <blockquote style="font-size:.88rem;line-height:1.7;color:var(--ink-2);font-style:italic;border-left:2px solid var(--gold);padding-left:1rem;margin:0;">
            {{ $analyse->situation }}
        </blockquote>
        @if($analyse->aspects && count($analyse->aspects))
        <div style="margin-top:.85rem;display:flex;flex-wrap:wrap;gap:.3rem;">
            @foreach($analyse->aspects as $asp)
            <span style="font-size:.71rem;font-family:'IBM Plex Mono',monospace;color:var(--ink-muted);background:var(--paper);border:1px solid var(--rule);padding:.15rem .5rem;border-radius:2px;">
                {{ str_replace('_',' ',$asp) }}
            </span>
            @endforeach
        </div>
        @endif
    </div>

    <p style="margin-top:1.5rem;font-size:.72rem;color:var(--ink-muted);line-height:1.5;border-top:1px solid var(--rule);padding-top:1rem;">
        Cette analyse est générée automatiquement à des fins académiques. Elle ne constitue pas un avis juridique professionnel et ne saurait engager la responsabilité de ses auteurs.
    </p>

</div>
@endsection
