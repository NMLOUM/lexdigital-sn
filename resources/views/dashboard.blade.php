@extends('layouts.app')

@section('title', 'Mes analyses')

@section('content')
<div class="container">

    {{-- EN-TÊTE --}}
    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:2.5rem;">
        <div>
            <div style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:.6rem;">
                Espace personnel · {{ auth()->user()->email }}
            </div>
            <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;color:var(--ink);line-height:1.2;">
                Mes analyses juridiques
            </h1>
        </div>
        <a href="{{ route('home') }}" class="btn btn-secondary">Nouvelle analyse</a>
    </div>

    {{-- STATISTIQUES --}}
    <div class="stat-row">
        <div class="stat-cell">
            <div class="stat-num">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-lbl">Analyses au total</div>
        </div>
        <div class="stat-cell">
            <div class="stat-num danger">{{ $stats['high'] ?? 0 }}</div>
            <div class="stat-lbl">Risque élevé</div>
        </div>
        <div class="stat-cell">
            <div class="stat-num warn">{{ $stats['medium'] ?? 0 }}</div>
            <div class="stat-lbl">Risque modéré</div>
        </div>
        <div class="stat-cell">
            <div class="stat-num ok">{{ $stats['low'] ?? 0 }}</div>
            <div class="stat-lbl">Risque faible</div>
        </div>
    </div>

    {{-- FILTRES --}}
    @php $currentFiltre = request('niveau_risque'); @endphp
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <span style="font-size:.72rem;font-family:'IBM Plex Mono',monospace;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-muted);">Filtrer</span>
        <form method="GET" action="{{ route('dashboard') }}" style="display:flex;gap:.5rem;flex-wrap:wrap;">
            @foreach([''=>'Tous','high'=>'Risque élevé','medium'=>'Risque modéré','low'=>'Risque faible'] as $val=>$label)
            <label style="display:flex;align-items:center;gap:.4rem;cursor:pointer;font-size:.83rem;font-weight:400;text-transform:none;letter-spacing:0;color:var(--ink-2);">
                <input type="radio" name="niveau_risque" value="{{ $val }}"
                       {{ ($currentFiltre===$val || ($val==='' && !$currentFiltre)) ? 'checked' : '' }}
                       onchange="this.form.submit()"
                       style="accent-color:var(--accent);width:auto;">
                {{ $label }}
            </label>
            @endforeach
        </form>
    </div>

    {{-- TABLEAU --}}
    @if($analyses->count() > 0)
    <div class="card" style="overflow:auto;margin-bottom:1.25rem;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">Réf.</th>
                    <th>Domaine analysé</th>
                    <th>Plateforme</th>
                    <th>Contexte</th>
                    <th>Risque</th>
                    <th>Date</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analyses as $a)
                <tr>
                    <td>
                        <span style="font-family:'IBM Plex Mono',monospace;font-size:.73rem;color:var(--ink-muted);">#{{ $a->id }}</span>
                    </td>
                    <td style="font-weight:500;">
                        {{ ucwords(str_replace('_',' ',$a->categorie_probleme ?? $a->categorie)) }}
                    </td>
                    <td style="color:var(--ink-muted);font-size:.83rem;">
                        {{ ucfirst(str_replace('_',' ',$a->type_plateforme ?? '—')) }}
                    </td>
                    <td style="color:var(--ink-muted);font-size:.83rem;">
                        {{ ucfirst($a->contexte_geo ?? '—') }}
                    </td>
                    <td>
                        @php
                        $bMap = ['high'=>'badge-danger','medium'=>'badge-warn','low'=>'badge-ok'];
                        $lMap = ['high'=>'Élevé','medium'=>'Modéré','low'=>'Faible'];
                        $b = $a->niveau_risque ?? 'medium';
                        @endphp
                        <span class="badge {{ $bMap[$b] ?? '' }}">{{ $lMap[$b] ?? $b }}</span>
                    </td>
                    <td style="font-size:.78rem;font-family:'IBM Plex Mono',monospace;color:var(--ink-muted);">
                        {{ $a->created_at->format('d/m/Y') }}<br>
                        <span style="opacity:.6;font-size:.7rem;">{{ $a->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="{{ route('analyse.show', $a->id) }}" class="btn btn-secondary btn-sm">Voir</a>
                            <a href="{{ route('analyse.pdf', $a->id) }}" class="btn btn-secondary btn-sm" target="_blank">PDF</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($analyses->hasPages())
    <div style="display:flex;justify-content:flex-end;">
        {{ $analyses->links() }}
    </div>
    @endif

    @else
    <div class="card" style="padding:3.5rem 2rem;text-align:center;">
        <div style="font-family:'Playfair Display',serif;font-size:1.35rem;color:var(--ink);margin-bottom:.6rem;">
            Aucune analyse trouvée
        </div>
        <p style="color:var(--ink-muted);font-size:.88rem;margin-bottom:1.75rem;">
            @if($currentFiltre)
                Aucune analyse avec ce niveau de risque dans votre historique.
            @else
                Vous n'avez pas encore effectué d'analyse juridique.
            @endif
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="width:auto;display:inline-flex;padding:.75rem 1.75rem;">
            Lancer ma première analyse
        </a>
    </div>
    @endif

</div>
@endsection
